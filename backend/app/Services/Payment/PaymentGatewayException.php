<?php

namespace App\Services\Payment;

use RuntimeException;

/**
 * Provider-agnostic wrapper around whatever exception the underlying SDK
 * throws — callers (CheckoutService) catch this one type regardless of
 * which gateway is active.
 */
class PaymentGatewayException extends RuntimeException {}
