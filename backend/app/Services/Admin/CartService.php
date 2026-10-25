<?php

namespace App\Services\Admin;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Builder;

class CartService extends BaseCrudService
{
    protected string $model = Cart::class;

    protected array $with = ['user', 'items.product', 'items.variant'];

    public function abandonedCarts(int $olderThanHours = 48, int $perPage = 15)
    {
        return Cart::with($this->with)
            ->where('status', 'active')
            ->where('updated_at', '<=', now()->subHours($olderThanHours))
            ->paginate($perPage);
    }

    public function markAbandoned(string $id): Cart
    {
        $cart = Cart::findOrFail($id);
        $cart->update(['status' => 'abandoned']);

        return $cart;
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($filters['user_id'] ?? null, fn ($q, $u) => $q->where('user_id', $u));
    }
}
