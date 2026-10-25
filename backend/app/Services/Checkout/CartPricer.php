<?php

namespace App\Services\Checkout;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Validation\ValidationException;

/**
 * One job: turn a cart's items into priced order-item payloads, locking
 * stock rows and re-validating against the LIVE quantity as it goes (not
 * the cart's cached snapshot) — pulled out of CheckoutService so pricing
 * logic has exactly one place to live and one reason to change.
 */
class CartPricer
{
    /**
     * @return array{0: array<int, array{stockable: Product|ProductVariant, order_item: array}>, 1: float}
     *
     * @throws ValidationException
     */
    public function priceCartItems(Cart $cart): array
    {
        $lineItems = [];
        $subtotal = 0.0;

        foreach ($cart->items as $item) {
            $stockable = $item->variant_id
                ? ProductVariant::where('id', $item->variant_id)->lockForUpdate()->firstOrFail()
                : Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();

            $product = $item->variant_id ? Product::findOrFail($item->product_id) : $stockable;

            if ($stockable->stock_quantity < $item->quantity) {
                throw ValidationException::withMessages([
                    'cart' => "\"{$product->name}\" no longer has enough stock ({$stockable->stock_quantity} left).",
                ]);
            }

            $price = $item->variant_id ? ($stockable->price_override ?? $product->price) : $product->price;
            $lineSubtotal = round($price * $item->quantity, 2);
            $subtotal += $lineSubtotal;

            $lineItems[] = [
                'stockable' => $stockable,
                'order_item' => [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name_snapshot' => $product->name,
                    'price_snapshot' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineSubtotal,
                ],
            ];
        }

        return [$lineItems, round($subtotal, 2)];
    }
}
