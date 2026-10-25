<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(protected CheckoutService $checkout) {}

    /**
     * Handles the "user enters address + delivery + payment" step:
     * creates or updates the order from the cart (see CheckoutService for
     * the full breakdown) and opens a payment session through whichever
     * gateway PaymentGatewayFactory resolves (Stripe by default). The
     * response includes `checkout_url` — redirect the browser there (or
     * open it in the mobile app's in-app browser) immediately; that's the
     * provider's own hosted payment page, not something to render locally.
     * The order becomes `paid` only once the provider's webhook confirms
     * it, not as a direct result of this response.
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $result = $this->checkout->checkout($request->user(), $request->validated());
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->created($result, 'Checkout session created — redirect the user to checkout_url');
    }
}
