<?php

namespace App\Services\Checkout;

use App\Models\Address;
use App\Models\User;

/**
 * One job: turn whatever address info a checkout request sent — either a
 * reference to an existing saved address, or a freshly typed-in one — into
 * an Address row that belongs to the user, per the flow's "user enters
 * address" step. Keeps that either/or branching out of CheckoutService.
 *
 * Note: `orders.shipping_address_id` is a required foreign key (per the
 * ERD), so an inline address is always persisted — there's no "use this
 * address just once without saving it" option, the order has to point at
 * a real row either way. The customer can delete it from their address
 * book afterward if they don't want to keep it.
 */
class AddressResolver
{
    /**
     * @param  array{
     *     shipping_address_id?: ?string,
     *     shipping_address?: ?array,
     *     billing_address_id?: ?string,
     * }  $data
     * @return array{0: Address, 1: ?Address} [shipping, billing]
     */
    public function resolve(User $user, array $data): array
    {
        $shipping = $this->resolveOne($user, $data['shipping_address_id'] ?? null, $data['shipping_address'] ?? null);

        $billing = ! empty($data['billing_address_id'])
            ? Address::where('user_id', $user->id)->findOrFail($data['billing_address_id'])
            : null;

        return [$shipping, $billing];
    }

    protected function resolveOne(User $user, ?string $addressId, ?array $inline): Address
    {
        if ($addressId) {
            return Address::where('user_id', $user->id)->findOrFail($addressId);
        }

        return Address::create([...$inline, 'user_id' => $user->id]);
    }
}
