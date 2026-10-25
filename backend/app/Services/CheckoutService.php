<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Checkout\AddressResolver;
use App\Services\Checkout\CartPricer;
use App\Services\Checkout\DeliveryFeeCalculator;
use App\Services\Payment\PaymentGatewayException;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Orchestrates the flow:
 *   authenticate (route middleware) -> load cart -> price items (locks
 *   stock) -> resolve addresses -> calculate delivery fee -> calculate
 *   total -> create/update order -> create payment session -> return
 *   checkout URL.
 *
 * Each step beyond "orchestrate" is delegated to a single-purpose
 * collaborator (CartPricer, AddressResolver, DeliveryFeeCalculator,
 * PaymentGateway) — this class wires them together and owns the
 * transaction boundary, nothing else, per the Single Responsibility
 * Principle. Swapping how any one step works (e.g. a smarter tax engine,
 * a different payment provider) never requires touching this file.
 *
 * Safety nets:
 *  - Idempotent at the client level: retrying with the same
 *    `idempotency_key` returns/updates the SAME order instead of creating
 *    a second one.
 *  - Idempotent at the payment-provider level too: PaymentGateway sends
 *    its own idempotency key, so even if the check above somehow raced,
 *    the provider itself won't double-charge.
 *  - Stock is locked and re-checked against the live row inside the
 *    transaction (see CartPricer), not the cart's stale snapshot.
 *  - Nothing is charged synchronously — this only creates a hosted
 *    checkout session and returns its URL. The order becomes `paid` only
 *    when the provider's webhook confirms it (see StripeWebhookService) —
 *    the webhook, not this request, is the source of truth.
 */
class CheckoutService
{
    protected PaymentGateway $gateway;

    public function __construct(
        protected CartPricer $pricer,
        protected AddressResolver $addresses,
        protected DeliveryFeeCalculator $deliveryFees,
        protected SettingService $settings,
        PaymentGatewayFactory $gatewayFactory,
    ) {
        $this->gateway = $gatewayFactory->make();
    }

    /**
     * @param  array{
     *     shipping_address_id?: ?string,
     *     shipping_address?: ?array,
     *     billing_address_id?: ?string,
     *     delivery_method: string,
     *     idempotency_key: string,
     * }  $data
     * @return array{order: Order, checkout_url: ?string, checkout_session_id: ?string}
     */
    public function checkout(User $user, array $data): array

    {
         $cart = Cart::where('user_id', $user->id)
                     ->where('status', 'active')
                     ->with('items')->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }
        
        $key = hash(
            'sha256',
            $user->id . ':' . $cart->id
        );

        $data['idempotency_key'] =  $key;


        $existingOrder = Order::where('idempotency_key', $data['idempotency_key'])->first();

        // Already progressed past "awaiting payment" (paid/cancelled/etc.)
        // -> this request can't change anything, just hand back what exists.
        if ($existingOrder && $existingOrder->status !== 'pending') {
            return $this->respond($existingOrder);
        }

        [$shippingAddress, $billingAddress] = $this->addresses->resolve($user, $data);

        return DB::transaction(fn () => $existingOrder
            ? $this->updateOrder($existingOrder, $cart, $data, $shippingAddress, $billingAddress)
            : $this->createOrder($user, $cart, $data, $shippingAddress, $billingAddress)
        );
    }

    protected function createOrder(User $user, Cart $cart, array $data, Address $shipping, ?Address $billing): array
    {
        [$lineItems, $subtotal] = $this->pricer->priceCartItems($cart);
        $totals = $this->calculateTotals($subtotal, $data['delivery_method']);

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $this->generateOrderNumber(),
            'status' => 'pending',
            'subtotal' => $totals['subtotal'],
            'tax' => $totals['tax'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'currency' => $this->settings->get('currency'),
            'shipping_address_id' => $shipping->id,
            'billing_address_id' => $billing?->id,
            'idempotency_key' => $data['idempotency_key'],
        ]);

        $this->applyLineItems($order, $lineItems);

        $payment = $this->openPaymentSession($order, $totals, $user);

        return $this->buildResponse($order->fresh('items'), $payment);
    }

    /**
     * "Update" path: the client is retrying checkout with a changed
     * address/delivery method before ever paying. Re-prices the (still
     * live) cart, replaces the order_items and totals, expires the old
     * payment session, and opens a new one — instead of leaving stale
     * line items or a payment link for the wrong amount.
     */
    protected function updateOrder(Order $order, Cart $cart, array $data, Address $shipping, ?Address $billing): array
    {
        [$lineItems, $subtotal] = $this->pricer->priceCartItems($cart);
        $totals = $this->calculateTotals($subtotal, $data['delivery_method']);

        $order->update([
            'subtotal' => $totals['subtotal'],
            'tax' => $totals['tax'],
            'shipping_fee' => $totals['shipping_fee'],
            'total' => $totals['total'],
            'shipping_address_id' => $shipping->id,
            'billing_address_id' => $billing?->id,
        ]);

        // Release stock held by the previous line items before re-applying
        // the fresh set, so re-checkout doesn't double-reserve inventory.
        $this->releaseLineItems($order);
        $order->items()->delete();
        $this->applyLineItems($order, $lineItems);

        if ($oldSessionId = $order->payment?->checkout_session_id) {
            $this->gateway->expireCheckoutSession($oldSessionId);
        }

        $payment = $this->openPaymentSession($order, $totals, $order->user);

        return $this->buildResponse($order->fresh('items'), $payment);
    }

    protected function calculateTotals(float $subtotal, string $deliveryMethod): array
    {
        $tax = round($subtotal * (float) $this->settings->get('tax_rate'), 2);
        $shippingFee = $this->deliveryFees->calculate($deliveryMethod, $subtotal);
        $total = round($subtotal + $tax + $shippingFee, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'total' => $total,
        ];
    }

    protected function applyLineItems(Order $order, array $lineItems): void
    {
        foreach ($lineItems as $line) {
            $order->items()->create($line['order_item']);
            $line['stockable']->decrement('stock_quantity', $line['order_item']['quantity']);
        }
    }

    protected function releaseLineItems(Order $order): void
    {
        foreach ($order->items as $item) {
            $item->variant_id
                ? $item->variant?->increment('stock_quantity', $item->quantity)
                : $item->product?->increment('stock_quantity', $item->quantity);
        }
    }

    protected function openPaymentSession(Order $order, array $totals, User $user): Payment
    {
        $checkoutLineItems = $order->items->map(fn ($item) => [
            'name' => $item->product_name_snapshot,
            'unit_amount' => $item->price_snapshot,
            'quantity' => $item->quantity,
        ])->all();

        if ($totals['tax'] > 0) {
            $checkoutLineItems[] = ['name' => 'Tax', 'unit_amount' => $totals['tax'], 'quantity' => 1];
        }

        if ($totals['shipping_fee'] > 0) {
            $checkoutLineItems[] = ['name' => 'Delivery', 'unit_amount' => $totals['shipping_fee'], 'quantity' => 1];
        }

        try {
            $result = $this->gateway->createCheckoutSession(
                $order,
                $checkoutLineItems,
                $order->currency,
                config('services.stripe.checkout_success_url').'/order_id='.$order->id.'?session_id={CHECKOUT_SESSION_ID}',
                config('services.stripe.checkout_cancel_url').'/order_id='.$order->id,
                ['order_id' => $order->id, 'order_number' => $order->order_number],
            );
        } catch (PaymentGatewayException $e) {
            report($e);

            throw ValidationException::withMessages(['payment' => 'Could not start checkout. Please try again.']);
        }

        return Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $user->id,
                'provider' => $this->gateway->name(),
                'checkout_session_id' => $result->sessionId,
                'idempotency_key' => (string) Str::uuid(),
                'amount' => $order->total,
                'currency' => $order->currency,
                'status' => 'pending',
            ]
        );
    }

    protected function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }

    protected function buildResponse(Order $order, Payment $payment): array
    {
        return [
            'order' => $order,
            'checkout_url' => $this->gateway->retrieveCheckoutSessionUrl($payment->checkout_session_id),
            'checkout_session_id' => $payment->checkout_session_id,
        ];
    }

    protected function respond(Order $order): array
    {
        return [
            'order' => $order->load('items'),
            'checkout_url' => null,
            'checkout_session_id' => $order->payment?->checkout_session_id,
        ];
    }
}