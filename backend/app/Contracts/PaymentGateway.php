<?php

namespace App\Contracts;

use App\DataTransferObjects\CheckoutSessionResult;
use App\Models\Order;

/**
 * Every payment provider CheckoutService can use implements this. Adding
 * PayPal/Paymob/whatever later means writing one new class against this
 * contract and registering it in PaymentGatewayFactory — CheckoutService
 * itself never changes (Open/Closed Principle).
 */
interface PaymentGateway
{
    /**
     * @param  array<int, array{name: string, unit_amount: float, quantity: int}>  $lineItems  provider-agnostic line items — this class translates them into whatever shape its SDK wants
     * @param  array<string, string>  $metadata
     */
    public function createCheckoutSession(
        Order $order,
        array $lineItems,
        string $currency,
        string $successUrl,
        string $cancelUrl,
        array $metadata = [],
    ): CheckoutSessionResult;

    /**
     * Invalidates an unpaid session — used when an in-progress order is
     * updated (new address/delivery method) and a fresh session is issued,
     * so the old payment link can't be completed after the fact.
     */
    public function expireCheckoutSession(string $sessionId): void;

    public function retrieveCheckoutSessionUrl(string $sessionId): ?string;

        public function refundPayment(
        string $paymentIntentId,
        ?int $amount = null,
        array $metadata = [],
        string $idempotency_key,
    ): string;

    /**
     * The provider name this gateway answers to in config/services.php
     * and PaymentGatewayFactory — e.g. 'stripe'.
     */
    public function name(): string;
}