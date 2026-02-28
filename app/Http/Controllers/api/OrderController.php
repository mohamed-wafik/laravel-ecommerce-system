<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return $this->sendResponse($orders, 'Orders retrieved successfully');
    }
    
    public function store(StoreOrderRequest $request)
    {
        $validator = $request->validate();
        
        $user = $request->user();

        $carts = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($carts->isEmpty()) {
            return $this->sendError('Cart is empty', [], 400);
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItems = [];

            foreach ($carts as $cart) {
                $product = Product::findOrFail($cart->product_id);
                
                if ($product->stock < $cart->quantity) {
                    return response()->json([
                        'error' => "Product '{$product->name}' has insufficient stock"
                    ], 400);
                }

                $itemTotal = $product->price * $cart->quantity;
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $cart->quantity,
                    'price' => $product->price,
                    'total' => $itemTotal
                ];
            }

            // Shipping cost
            $shippingCost = config("payment.shipping_rates.{$request->shipping_method}", 30);
            
            $discount = 0;
            if ($request->coupon_code) {
                $discount = $this->applyCoupon($request->coupon_code, $subtotal);
            }
            
            // Tax
            $taxableAmount = $subtotal - $discount;
            $tax = round($taxableAmount * config('payment.tax_rate'), 2);
            
            // Total
            $total = round($taxableAmount + $shippingCost + $tax, 2);

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => "cod",
                'payment_status' => 'pending',
                'order_status' => 'pending'
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ]);
            }

            if ($request->payment_method === 'cod') {
                foreach ($orderItems as $item) {
                    Product::find($item['product_id'])->decrement('stock', $item['quantity']);
                }
                $order->update(['order_status' => 'processing']);
            }
            
            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return $this->sendResponse($order, 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Order creation failed', ['error' => $e->getMessage()], 500);
        }
    }

    private function applyCoupon($code, $subtotal)
    {
        // Simple coupon logic - you can expand this
        $coupons = [
            'SAVE10' => 0.10, // 10% off
            'SAVE20' => 0.20, // 20% off
            'FLAT50' => 50,   // Flat 50 EGP off
        ];

        if (isset($coupons[$code])) {
            $discount = $coupons[$code];
            return $discount < 1 ? $subtotal * $discount : $discount;
        }

        return 0;
    }

    public function show($orderNumber)
    {
        $order = Order::where('id', $orderNumber)
            ->with('items.product')
            ->firstOrFail();
        
        return $this->sendResponse($order, 'Order details retrieved successfully');
    }

    public function updatePaymentStatus(Request $request, $orderId)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);
        
        $order = Order::findOrFail($orderId);
        
        $order->update([
            'payment_status' => $request->payment_status,
            'order_status' => $request->payment_status === 'paid' ? 'processing' : 'pending'
        ]);

        // Reduce stock when payment is confirmed
        if ($request->payment_status === 'paid') {
            foreach ($order->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        return $this->sendResponse($order, 'Payment status updated successfully');
    }
}