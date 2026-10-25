<?php

// namespace App\Services\Guest;

// use App\Models\Cart;
// use App\Models\Product;
// use App\Models\ProductVariant;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;
// use Illuminate\Validation\ValidationException;

// /**
//  * Cart for anonymous visitors, keyed by an opaque `guest_token` the
//  * frontend generates once and sends back as `X-Guest-Token` on every
//  * request (localStorage/cookie on web, secure storage on mobile) — the
//  * same pattern Amazon/Noon/most storefronts use so "add to cart" works
//  * before signup. On login/register-verify, AuthController merges this
//  * cart into the now-known user's cart (see mergeIntoUser()).
//  */
// class GuestCartService
// {
//     public function newGuestToken(): string
//     {
//         return (string) Str::uuid();
//     }

//     public function getOrCreate(string $guestToken): Cart
//     {
//         return Cart::firstOrCreate(
//             ['guest_token' => $guestToken, 'status' => 'active'],
//             ['guest_token' => $guestToken, 'status' => 'active']
//         );
//     }

//     public function view(string $guestToken): Cart
//     {
//         return $this->getOrCreate($guestToken)
//                     ->load('items.product.images', 'items.variant');
//     }

//     public function addItem(string $guestToken, string $productId, ?string $variantId, int $quantity): Cart
//     {
//         return DB::transaction(function () use ($guestToken, $productId, $variantId, $quantity) {
//             $cart = $this->getOrCreate($guestToken);

//             $product = Product::where('is_active', true)
//                                 ->findOrFail($productId);
//             $variant = $variantId ? ProductVariant::where('product_id', $productId)->findOrFail($variantId) : null;

//             $availableStock = $variant?->stock_quantity ?? $product->stock_quantity;
//             if ($availableStock < $quantity) {
//                 throw ValidationException::withMessages(['quantity' => 'Not enough stock available.']);
//             }

//             $price = $variant?->effective_price ?? $product->price;

//             $item = $cart->items()
//                          ->where('product_id', $productId)
//                          ->where('variant_id', $variantId)
//                          ->first();

//             if ($item) {
//                 $item->update(['quantity' => $item->quantity + $quantity, 'price_snapshot' => $price]);
//             } else {
//                 $cart->items()->create([
//                     'product_id' => $productId,
//                     'variant_id' => $variantId,
//                     'quantity' => $quantity,
//                     'price_snapshot' => $price,
//                 ]);
//             }

//             return $cart->fresh(['items.product.images', 'items.variant']);
//         });
//     }

//     public function updateItemQuantity(string $guestToken, string $itemId, int $quantity): Cart
//     {
//         $cart = $this->getOrCreate($guestToken);
//         $item = $cart->items()
//                      ->findOrFail($itemId);

//         if ($quantity <= 0) {
//             $item->delete();
//         } else {
//             $item->update(['quantity' => $quantity]);
//         }

//         return $cart->fresh(['items.product.images', 'items.variant']);
//     }

//     public function removeItem(string $guestToken, string $itemId): Cart
//     {
//         $cart = $this->getOrCreate($guestToken);
//         $cart->items()
//              ->where('id', $itemId)
//              ->delete();

//         return $cart->fresh(['items.product.images', 'items.variant']);
//     }

//     /**
//      * Called right after register/login succeeds. If the visitor already
//      * has an account cart, guest items are folded into it (quantities
//      * combined) and the guest cart is discarded; otherwise the guest cart
//      * simply becomes the user's cart. Never lose items either side had.
//      */
//     public function mergeIntoUser(string $guestToken, string $userId): void
//     {
//         DB::transaction(function () use ($guestToken, $userId) {
//             $guestCart = Cart::where('guest_token', $guestToken)
//                               ->where('status', 'active')
//                               ->first();

//             if (! $guestCart) {
//                 return;
//             }

//             $userCart = Cart::where('user_id', $userId)
//                               ->where('status', 'active')
//                               ->first();

//             if (! $userCart) {
//                 $guestCart->update(['user_id' => $userId, 'guest_token' => null]);

//                 return;
//             }

//             foreach ($guestCart->items as $guestItem) {
//                 $existing = $userCart->items()
//                     ->where('product_id', $guestItem->product_id)
//                     ->where('variant_id', $guestItem->variant_id)
//                     ->first();

//                 if ($existing) {
//                     $existing->update(['quantity' => $existing->quantity + $guestItem->quantity]);
//                 } else {
//                     $userCart->items()->create([
//                         'product_id' => $guestItem->product_id,
//                         'variant_id' => $guestItem->variant_id,
//                         'quantity' => $guestItem->quantity,
//                         'price_snapshot' => $guestItem->price_snapshot,
//                     ]);
//                 }
//             }

//             $guestCart->items()->delete();
//             $guestCart->delete();
//         });
//     }
// }