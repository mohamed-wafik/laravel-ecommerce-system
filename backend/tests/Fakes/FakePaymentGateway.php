<?php

namespace Tests\Fakes;

use App\Contracts\PaymentGateway;
use App\DataTransferObjects\CheckoutSessionResult;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Fulfils the same PaymentGateway contract StripePaymentGateway does —
 * that's the whole point of building checkout against an interface
 * (Liskov substitution): CheckoutService can't tell the difference, so
 * the entire checkout flow is testable without a real Stripe account or
 * network access. Records every call so tests can assert "a session was
 * created for this order" etc.
 */
class FakePaymentGateway implements PaymentGateway
{
    public array $createdSessions = [];

    public array $expiredSessions = [];

    public bool $shouldFail = false;

    public function createCheckoutSession(
        Order $order,
        array $lineItems,
        string $currency,
        string $successUrl,
        string $cancelUrl,
        array $metadata = [],
    ): CheckoutSessionResult {
        if ($this->shouldFail) {
            throw new \App\Services\Payment\PaymentGatewayException('Simulated gateway failure');
        }

        $sessionId = 'cs_test_'.Str::random(20);

        $this->createdSessions[] = [
            'order_id' => $order->id,
            'session_id' => $sessionId,
            'line_items' => $lineItems,
            'currency' => $currency,
        ];

        return new CheckoutSessionResult($sessionId, "https://checkout.fake.test/pay/{$sessionId}");
    }

    public function expireCheckoutSession(string $sessionId): void
    {
        $this->expiredSessions[] = $sessionId;
    }

    public function retrieveCheckoutSessionUrl(string $sessionId): ?string
    {
        return "https://checkout.fake.test/pay/{$sessionId}";
    }

    public function name(): string
    {
        return 'fake';
    }
}
