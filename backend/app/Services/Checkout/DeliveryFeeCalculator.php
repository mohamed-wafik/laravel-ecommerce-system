<?php

namespace App\Services\Checkout;

use App\Services\SettingService;
use InvalidArgumentException;

/**
 * One job: given a delivery method and an order subtotal, what's the fee?
 * Rates come from SettingService (admin-editable), not a hardcoded
 * constant, so this and the admin settings screen never drift apart.
 */
class DeliveryFeeCalculator
{
    public function __construct(protected SettingService $settings) {}

    public function calculate(string $method, float $subtotal): float
    {
        $threshold = (float) $this->settings->get('free_delivery_threshold');

        if ($threshold > 0 && $subtotal >= $threshold) {
            return 0.0;
        }

        return match ($method) {
            'standard' => (float) $this->settings->get('delivery_fee_standard'),
            'express' => (float) $this->settings->get('delivery_fee_express'),
            default => throw new InvalidArgumentException("Unknown delivery method [{$method}]."),
        };
    }
}
