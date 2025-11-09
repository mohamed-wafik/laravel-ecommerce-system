<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ItemOrder;
use App\Models\Product;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->user();
        $orders = $user->orders()->with('itemorders.product')->get();

        return response()->json([
            'data' => $orders,
            'message' => 'Orders retrieved successfully',
            'status' => 200,
        ]);
    }

    public function show(Request $req, $id)
    {
        $user = $req->user();
        $order = $user->orders()->with('itemOrders.product')->find($id);

        if (!$order) {
            return response()->json([
                'data' => null,
                'message' => 'Order not found',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'data' => $order,
            'message' => 'Order retrieved successfully',
            'status' => 200,
        ]);
    }
    public function store(Request $req)
    {
        $req->validate([
            'country' => 'required|string|max:100',
            'data' => 'required|array',
            'data.*.id' => 'required|integer|exists:products,id',
            'data.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $req->user();
        $country = $req->input('country');
        $products = $req->input('data'); 

        $totalAmount = 0;
        $itemOrders = [];

        foreach ($products as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                return response()->json([
                    'data' => null,
                    'message' => 'Product not found',
                    'status' => 404,
                ], 404);
            }

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'data' => null,
                    'message' => 'Insufficient stock for product: ' . $product->title,
                    'status' => 400,
                ], 400);
            }

            $discount = $product->discount ?? 0;
            $priceAfterDiscount = $product->price - ($product->price * $discount);
            $subtotal = $priceAfterDiscount * $item['quantity'];
            $totalAmount += $subtotal;

            $itemOrders[] = [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $priceAfterDiscount,
                'subtotal' => $subtotal,
            ];
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionPayment = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order Payment',
                    ],
                    'unit_amount' => intval($totalAmount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => env('FRONTEND_URL') . '/payment-success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('FRONTEND_URL') . '/payment-cancel',
        ]);

        $order = $user->orders()->create([
            'country' => $country,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'payment_tsession_id' => $sessionPayment->id, 
            'payment_id' => $sessionPayment->url,
        ]);

        foreach ($itemOrders as $itemOrder) {
            $order->itemOrders()->create($itemOrder);
        }

        return response()->json([
            'data' => ['payment_url' => $sessionPayment->url],
            'message' => 'Order created successfully',
            'status' => 201,
        ], 201);
    }
}