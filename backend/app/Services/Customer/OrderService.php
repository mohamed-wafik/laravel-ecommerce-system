<?php

namespace App\Services\Customer;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * A customer's own order history — every method is implicitly scoped to
 * the given $user, same pattern as Customer\AddressService. There is no
 * "get any order by id" here; that's Admin\OrderService's job. Keeping
 * them as two separate classes means this one can never accidentally leak
 * another customer's order just because a route forgot to scope a query.
 */
class OrderService
{
    protected array $with = ['items', 'payment', 'shippingAddress'];

    public function list(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->orders()
            ->with($this->with)
            ->latest()
            ->paginate($perPage);
    }

    public function find(User $user, string $orderId): Order
    {
        return $user->orders()->with($this->with)->findOrFail($orderId);
    }
}