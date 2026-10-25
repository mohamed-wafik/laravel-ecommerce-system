<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Either `shipping_address_id` (an existing saved address) OR
 * `shipping_address` (typed fresh on the checkout page) must be present —
 * matches "user enters address + delivery + payment" on the checkout
 * screen, where most shoppers type a new address rather than picking a
 * saved one.
 *
 * `idempotency_key` MUST be generated once by the client (a UUID) when the
 * checkout screen loads and reused on every retry (dropped connection,
 * double-tapped "Place order", or resubmitting after changing the address
 * before paying) — CheckoutService treats a repeat as the same order and
 * updates it in place instead of creating a duplicate.
 */
class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address_id' => ['required_without:shipping_address', 'nullable', 'uuid'],
            'shipping_address' => ['required_without:shipping_address_id', 'nullable', 'array'],
            'shipping_address.full_name' => ['required_with:shipping_address', 'string', 'max:255'],
            'shipping_address.phone' => ['required_with:shipping_address', 'string', 'max:30'],
            'shipping_address.country' => ['required_with:shipping_address', 'string', 'max:100'],
            'shipping_address.city' => ['required_with:shipping_address', 'string', 'max:100'],
            'shipping_address.street' => ['required_with:shipping_address', 'string', 'max:255'],
            'shipping_address.postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_address.label' => ['nullable', 'string', 'max:50'],

            'billing_address_id' => ['nullable', 'uuid'],

            'delivery_method' => ['required', 'in:standard,express'],
        ];
    }
}