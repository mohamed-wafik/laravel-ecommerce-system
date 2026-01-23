<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\api\BaseController;
use App\Models\Cart;
use Illuminate\Http\Request;   
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartResource;

class CartController extends BaseController
{
    public function index()
    {
        $itemCart = Cart::with([
                'product' => function ($query) {
                    $query->withCount('reviews')             
                        ->withAvg('reviews', 'rating');     
                }
            ])
            ->where('user_id', Auth::id())
            ->get();

        return $this->sendResponse(CartResource::collection($itemCart), 'Cart items retrieved successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->lockForUpdate()
            ->first();

        if ($cartItem) {

            $cartItem->quantity += $quantity;
            $cartItem->save();

            return $this->sendResponse(CartResource::make($cartItem), 'Cart item updated');
        }

        $newCart = Cart::create([
            'user_id'    => $userId,
            'product_id' => $productId,
            'quantity'   => $quantity
        ]);

        return $this->sendResponse(CartResource::make($newCart), 'Product added to cart successfully');
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$cart) {
            return $this->sendError('Cart item not found', [], 404);
        }

        $cart->update([
            'quantity' => $validated['quantity']   // FIXED: correct usage
        ]);

        return $this->sendResponse(CartResource::make($cart), 'Cart item updated successfully');
    }
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$cart) {
            return $this->sendError('Cart item not found', [], 404);
        }

        $cart->delete();

        return $this->sendResponse([], 'Cart item deleted successfully');
    }
}