<?php

namespace App\DataTransferObjects;

/**
 * Provider-agnostic result of starting a hosted checkout — whatever the
 * gateway (Stripe today, others later), CheckoutService only ever deals
 * with this shape, never a provider SDK object directly.
 */
final class CheckoutSessionResult
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $url,
    ) {}
}
