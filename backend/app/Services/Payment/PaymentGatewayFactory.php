<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use InvalidArgumentException;
use Stripe\StripeClient;

/**
 * Factory pattern: callers ask for "the payment gateway" by name (or let
 * it default to config), and get back something implementing
 * PaymentGateway without knowing or caring how it's constructed. Adding a
 * second provider is a new `match` arm here — nothing else in the app
 * changes, satisfying the Open/Closed Principle from the caller's side.
 */
class PaymentGatewayFactory
{
    /**
     * @var array<string, PaymentGateway> per-request instance cache — a
     *      checkout that calls make('stripe') twice gets the same client.
     */
    protected array $instances = [];

    public function make(?string $provider = null): PaymentGateway
    {
        $provider ??= config('services.payment.default', 'stripe');

        return $this->instances[$provider] ??= $this->build($provider);
    }

    protected function build(string $provider): PaymentGateway
    {
        return match ($provider) {
            'stripe' => new StripePaymentGateway(new StripeClient(config('services.stripe.secret'))),

            // Adding PayPal later looks like:
            // 'paypal' => new PayPalPaymentGateway(new PayPalClient(config('services.paypal'))),

            default => throw new InvalidArgumentException("Unsupported payment provider [{$provider}]."),
        };
    }
}
