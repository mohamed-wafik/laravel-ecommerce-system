<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ItemOrder;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Stripe\Stripe;

class OrderController extends BaseController
{
    public function index(Request $req)
    {
        $user = $req->user();

        $orders = $user->orders()->with('itemOrders.product')->get();

        return $this->sendResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

    public function show(Request $req, $id)
    {
        $user = $req->user();

        $order = $user->orders()
                      ->with('itemOrders.product')
                      ->find($id);

        if (!$order) {
            return $this->sendError('Order not found', [], 404);
        }

        return $this->sendResponse(OrderResource::make($order), 'Order retrieved successfully');
    }

    /**
     * Create order using CART TABLE
     */
    public function store(Request $req)
    {
        $req->validate([
            'country' => 'required|string|max:100',
        ]);

        $user = $req->user();

        $carts = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($carts->isEmpty()) {
            return $this->sendError('Cart is empty', [], 400);
        }

        $totalAmount = 0;
        $lineItems = [];
        $itemOrders = [];

        DB::beginTransaction();

        try {
            foreach ($carts as $cart) {
                $product = Product::where('id', $cart->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception('A product in cart does not exist anymore.');
                }

                if ($product->stock < $cart->quantity) {
                    throw new \Exception("Insufficient stock for: {$product->title}");
                }

                $product->decrement('stock', $cart->quantity);

                $discount = $product->discount ?? 0;
                $priceAfterDiscount = $product->price * (1 - $discount);
                $subtotal = $priceAfterDiscount * $cart->quantity;

                $totalAmount += $subtotal;

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->title,
                            'images' => [$product->image],
                        ],
                        'unit_amount' => (int) round($priceAfterDiscount * 100),
                    ],
                    'quantity' => $cart->quantity,
                ];

                $itemOrders[] = [
                    'product_id' => $product->id,
                    'quantity' => $cart->quantity,
                    'unit_price' => $priceAfterDiscount,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->sendError('Order creation failed', ['error' => $e->getMessage()], 400);
        }



        $order = $user->orders()->create([
            'country' => $req->country,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);


        foreach ($itemOrders as &$item) {
            $item['order_id'] = $order->id;
        }

        ItemOrder::insert($itemOrders);


        Cart::where('user_id', $user->id)->delete();

        $order->load('itemOrders.product');

        return $this->sendResponse(OrderResource::make($order), 'Order placed successfully');
        
    }

}