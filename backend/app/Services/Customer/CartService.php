<?php

namespace App\Services\Customer;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Same shape as Guest\GuestCartService but keyed by an authenticated
 * user instead of a guest token. Once GuestCartService::mergeIntoUser()
 * has run (on login/register-verify), this is what the customer's cart
 * lives in from then on — and what CheckoutService reads at checkout time.
 */
class CartService
{
    public function getOrCreate(User $user): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['user_id' => $user->id, 'status' => 'active']
        );
    }

    public function view(User $user): Cart
    {
    return $this->getOrCreate($user)
        ->load([
            'items.product' => function ($query) {
                $query->withAvg(['reviews as average_rating' => fn ($q) => $q->approved()], 'rating');
                $query->withCount(['reviews as approved_reviews_count' => fn ($q) => $q->approved()]);
            },
            'items.product.images',
            'items.variant',
        ]);
    }

    public function addItem(User $user, string $productId, ?string $variantId, int $quantity): Cart
    {
        return DB::transaction(function () use ($user, $productId, $variantId, $quantity) {
            $cart = $this->getOrCreate($user);

            $product = Product::where('is_active', true)
                                ->findOrFail($productId);
            $variant = $variantId ? 
                        ProductVariant::where('product_id', $productId)
                        ->findOrFail($variantId) 
                        : null;

            $availableStock = $variant?->stock_quantity ?? $product->stock_quantity;
            if ($availableStock < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'Not enough stock available.']);
            }

            $price = $variant?->effective_price ?? $product->price;

            $item = $cart->items()
                         ->where('product_id', $productId)
                         ->where('variant_id', $variantId)
                         ->first();

            if ($item) {
                $item->update([
                    'quantity' => $item->quantity + $quantity, 
                    'price_snapshot' => $price
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price_snapshot' => $price,
                ]);
            }

            return $cart->fresh(['items.product.images', 'items.variant']);
        });
    }

    public function updateItemQuantity(User $user, string $itemId, int $quantity): Cart
    {
        $cart = $this->getOrCreate($user);
        $item = $cart->items()
                     ->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $quantity]);
        }

        return $cart->fresh(['items.product.images', 'items.variant']);
    }

    public function removeItem(User $user, string $itemId): Cart
    {
        $cart = $this->getOrCreate($user);
        $cart->items()
             ->where('id', $itemId)
             ->delete();

        return $cart->fresh(['items.product.images', 'items.variant']);
    }
    public function clear(string $user_id) {
        Cart::where("user_id",$user_id)->delete();
    }
}