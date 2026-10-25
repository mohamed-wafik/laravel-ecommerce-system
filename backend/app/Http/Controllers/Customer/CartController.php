<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function __construct(protected CartService $carts) {}

    public function show(Request $request)
    {
        return $this->success($this->carts->view($request->user()));
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'uuid'],
            'variant_id' => ['nullable', 'uuid'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        try {
            $cart = $this->carts->addItem(
                $request->user(),
                $data['product_id'],
                $data['variant_id'] ?? null,
                $data['quantity']
            );
        } catch (ValidationException $e) {
            return $this->validationError($e->errors(), $e->getMessage());
        }

        return $this->success($cart, 'Added to cart');
    }

    public function updateItem(Request $request, string $itemId)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:50']]);

        return $this->success(
            $this->carts->updateItemQuantity($request->user(), $itemId, $data['quantity']),
            'Cart updated'
        );
    }

    public function removeItem(Request $request, string $itemId)
    {
        return $this->success($this->carts->removeItem($request->user(), $itemId), 'Item removed');
    }
    public function clear(Request $request) 
    {
        $this->carts->clear($request->user()->id);
        return $this->noContentSuccess("clear all item of cart");
    }

}