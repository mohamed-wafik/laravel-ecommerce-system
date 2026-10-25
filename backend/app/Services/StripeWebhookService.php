<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\WebhookEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeWebhookService
{
    /**
     * Handle a verified Stripe event. Idempotent: duplicate event IDs
     * (Stripe retries on timeout/non-2xx, and can send the same event
     * more than once) are recorded and skipped.
     */
    public function handle(object $event): void
    {
        if ($this->alreadyProcessed($event->id)) {
            return;
        }

        DB::transaction(function () use ($event) {
            match ($event->type) {
                'checkout.session.completed' => $this->onCheckoutSessionCompleted($event),
                'checkout.session.expired' => $this->onCheckoutSessionExpired($event),
                'payment_intent.succeeded' => $this->onPaymentSucceeded($event),
                'payment_intent.payment_failed' => $this->onPaymentFailed($event),
                'charge.refunded' => $this->onChargeRefunded($event),
                default => Log::info("Unhandled Stripe webhook type: {$event->type}"),
            };

            WebhookEvent::create([
                'provider' => 'stripe',
                'event_id' => $event->id,
                'type' => $event->type,
                'payload' => $event->toArray(),
                'processed_at' => now(),
            ]);
        });
    }

    protected function alreadyProcessed(string $eventId): bool
    {
        return WebhookEvent::where('provider', 'stripe')->where('event_id', $eventId)->exists();
    }

    /**
     * The primary "did checkout succeed" signal for the hosted Checkout
     * Session flow (CheckoutService::createCheckoutSession). Fires once
     * the customer completes payment on Stripe's page and lands back on
     * `success_url`. `payment_status` is checked because a session can
     * complete with a still-pending payment for delayed methods (e.g.
     * certain bank debits) — only `paid` marks the order paid here;
     * anything else is left for `payment_intent.succeeded` to confirm later.
     */
    protected function onCheckoutSessionCompleted(object $event): void
    {
        $session = $event->data->object;

        /** @var Payment|null $payment */
        $payment = Payment::where('checkout_session_id', $session->id)->lockForUpdate()->first();

        if (! $payment) {
            Log::warning("Stripe webhook: no local Payment for checkout session {$session->id}");

            return;
        }

        $payment->update(['payment_intent_id' => $session->payment_intent]);

        if ($session->payment_status === 'paid') {
            $payment->update(['status' => 'succeeded', 'paid_at' => now()]);
            $payment->order()->update(['status' => 'paid']);
            $this->convertCartFor($payment->order);
        }
    }

    /**
     * The customer abandoned Stripe's hosted page (closed the tab, didn't
     * finish within the session's time limit). We already reserved stock
     * and created the order at checkout time, so an expired session
     * releases both — cancel the order and put the stock back — instead
     * of holding inventory hostage for an order that will never be paid.
     */
    protected function onCheckoutSessionExpired(object $event): void
    {
        $session = $event->data->object;

        /** @var Payment|null $payment */
        $payment = Payment::where('checkout_session_id', $session->id)->lockForUpdate()->first();

        if (! $payment) {
            return;
        }

        $payment->update(['status' => 'failed']);

        /** @var Order $order */
        $order = $payment->order;

        if ($order->status !== 'pending') {
            return; // already progressed via some other path — don't clobber it
        }

        $order->update(['status' => 'cancelled']);

        foreach ($order->items as $item) {
            $item->variant_id
                ? $item->variant?->increment('stock_quantity', $item->quantity)
                : $item->product?->increment('stock_quantity', $item->quantity);
        }
    }

    protected function onPaymentSucceeded(object $event): void
    {
        $intent = $event->data->object;

        /** @var Payment|null $payment */
        $payment = Payment::where('payment_intent_id', $intent->id)->lockForUpdate()->first();

        if (! $payment) {
            Log::warning("Stripe webhook: no local Payment for intent {$intent->id}");

            return;
        }

        $payment->update([
            'status' => 'succeeded',
            'payment_method' => $intent->payment_method_types[0] ?? $payment->payment_method,
            'paid_at' => now(),
        ]);

        $payment->order()->update(['status' => 'paid']);
        $this->convertCartFor($payment->order);
    }

    /**
     * The cart deliberately stays `active` throughout checkout (see
     * CheckoutService) so a retried/updated checkout attempt can still
     * find and re-price it — it's only marked `converted` once payment
     * has actually succeeded, here, not at order-creation time.
     */
    protected function convertCartFor(Order $order): void
    {
        Cart::where('user_id', $order->user_id)
            ->where('status', 'active')
            ->update(['status' => 'converted']);
    }

    protected function onPaymentFailed(object $event): void
    {
        $intent = $event->data->object;

        Payment::where('payment_intent_id', $intent->id)
            ->lockForUpdate()
            ->first()
            ?->update(['status' => 'failed']);
    }

    protected function onChargeRefunded(object $event): void
    {
        $charge = $event->data->object;

        /** @var Payment|null $payment */
        $payment = Payment::where('payment_intent_id', $charge->payment_intent)
            ->lockForUpdate()
            ->first();

        if (! $payment) {
            return;
        }

        $payment->update(['status' => 'refunded']);

        /** @var Order $order */
        $order = $payment->order;
        $order->update(['status' => 'refunded']);

        foreach ($order->items as $item) {
            $item->variant_id
                ? $item->variant?->increment('stock_quantity', $item->quantity)
                : $item->product?->increment('stock_quantity', $item->quantity);
        }
    }
}