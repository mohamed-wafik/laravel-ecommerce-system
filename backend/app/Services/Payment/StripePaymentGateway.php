<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use App\DataTransferObjects\CheckoutSessionResult;
use App\Models\Order;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

/**
 * The only class in the app that talks to the Stripe SDK for checkout
 * sessions — CheckoutService depends on the PaymentGateway interface, not
 * this class, so swapping or adding a provider never touches checkout logic.
 */
class StripePaymentGateway implements PaymentGateway
{
    public function __construct(protected StripeClient $client) {}

    public function createCheckoutSession(
        Order $order,
        array $lineItems,
        string $currency,
        string $successUrl,
        string $cancelUrl,
        array $metadata = [],
    ): CheckoutSessionResult {
        try {
            $session = $this->client->checkout->sessions->create([
                'mode' => 'payment',
                'client_reference_id' => $order->id,
                'line_items' => array_map(
                    fn (array $item) => $this->toStripeLineItem($item, $currency),
                    $lineItems
                ),
                'metadata' => $metadata,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ], [
                'idempotency_key' => (string) Str::uuid(),
            ]);
        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException("Stripe checkout session creation failed: {$e->getMessage()}", previous: $e);
        }

        return new CheckoutSessionResult($session->id, $session->url);
    }

    public function expireCheckoutSession(string $sessionId): void
    {
        try {
            $session = $this->client->checkout->sessions->retrieve($sessionId);

            if ($session->status === 'open') {
                $this->client->checkout->sessions->expire($sessionId);
            }
        } catch (ApiErrorException $e) {
            // Already expired/completed/gone — nothing to do, and this
            // shouldn't block the order update that triggered it.
        }
    }

    public function retrieveCheckoutSessionUrl(string $sessionId): ?string
    {
        try {
            return $this->client->checkout->sessions->retrieve($sessionId)->url;
        } catch (ApiErrorException $e) {
            return null;
        }
    }

    public function name(): string
    {
        return 'stripe';
    }

    public function refundPayment(
        string $paymentIntentId,
        ?int $amount = null,
        array $metadata = [],
        string $idempotency_key
    ): string {
        try {
            $payload = [
                'payment_intent' => $paymentIntentId,
            ];

            if ($amount !== null) {
                $payload['amount'] = $amount;
            }

            if (!empty($metadata)) {
                $payload['metadata'] = $metadata;
            }

            $refund = $this->client->refunds->create(
                $payload,
                [
                    'idempotency_key' => $idempotency_key,
                ]
            );

            return $refund->id;
        } catch (ApiErrorException $e) {
            throw new PaymentGatewayException(
                "Stripe refund failed: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    protected function toStripeLineItem(array $item, string $currency): array
    {
        return [
            'price_data' => [
                'currency' => strtolower($currency),
                'product_data' => ['name' => $item['name']],
                'unit_amount' => (int) round($item['unit_amount'] * 100),
            ],
            'quantity' => $item['quantity'],
        ];
    }
}