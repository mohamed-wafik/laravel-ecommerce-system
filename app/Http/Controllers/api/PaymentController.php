<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\info;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Checkout Session (with return URL)
     */
    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            $order = Order::with('items.product')->findOrFail($request->order_id);

            if($order->user_id !== $request->user()->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if($order->payment_status === 'paid') {
                return response()->json(['error' => 'Order is already paid'], 400);
            }

            // Build line items for Stripe
            $lineItems = [];
            foreach ($order->items as $item) {

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'egp',
                        'product_data' => [
                            'name' => $item->product->title,
                            'description' => $item->product->description,
                            'images' => [$item->product->image],
                        ],
                        'unit_amount' => (int)($item->price * 100), // Convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Add shipping as line item
            if ($order->shipping_cost > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'egp',
                        'product_data' => [
                            'name' => 'Shipping - ' . ucfirst($order->shipping_method),
                        ],
                        'unit_amount' => (int)($order->shipping_cost * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Add tax as line item
            if ($order->tax > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'egp',
                        'product_data' => [
                            'name' => 'Tax (14%)',
                        ],
                        'unit_amount' => (int)($order->tax * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            if(empty($lineItems)) {
                return response()->json(['error' => 'No items to purchase'], 400);

                // info()->error();
            }

            foreach ($lineItems as $index => $item) {
                info("Line Item #{$index}: " . json_encode($item));
            }

            // Frontend URLs
            $frontendUrl  = "http://localhost:5174"; // Change to your frontend URL

            // Create Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $frontendUrl . '/payment/success?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                'cancel_url' => $frontendUrl . '/payment/cancel?order_id=' . $order->id,
                'customer_email' => $order->customer_email,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
                'client_reference_id' => $order->order_number,
            ]);

            // Store session ID in order
            $order->update([
                'payment_session_id' => $session->id
            ]);

            return response()->json([
                'url' => $session->url
            ]);

        } catch (\Exception $e) {
            Log::error('Checkout Session Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify Payment after return from Stripe
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            // Retrieve session from Stripe
            $session = Session::retrieve($request->session_id);
            
            $order = Order::findOrFail($request->order_id);

            if ($session->payment_status === 'paid') {
                DB::beginTransaction();

                // Update order status
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing',
                    'payment_intent_id' => $session->payment_intent
                ]);

                // Reduce stock
                foreach ($order->items as $item) {
                    $item->product->decrement('stock', $item->quantity);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'order' => $order->load('items.product')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment not completed',
                'payment_status' => $session->payment_status
            ], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Verification Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment cancellation
     */
    public function handleCancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            
            // Don't delete the order, just mark as cancelled
            $order->update([
                'payment_status' => 'failed',
                'order_status' => 'cancelled'
            ]);

            return response()->json([
                'message' => 'Order cancelled',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            Log::error('Cancel Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook Handler for Stripe Events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('payment.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );

            Log::info('Stripe Webhook Event: ' . $event->type);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    $this->handleCheckoutCompleted($session);
                    break;

                case 'checkout.session.expired':
                    $session = $event->data->object;
                    $this->handleCheckoutExpired($session);
                    break;

                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    Log::info('Payment succeeded: ' . $paymentIntent->id);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailed($paymentIntent);
                    break;

                default:
                    Log::info('Unhandled event type: ' . $event->type);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function handleCheckoutCompleted($session)
    {
        $orderId = $session->metadata->order_id ?? null;
        
        if ($orderId) {
            DB::beginTransaction();
            try {
                $order = Order::find($orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'order_status' => 'processing',
                        'payment_intent_id' => $session->payment_intent,
                        'paid_at' => now()
                    ]);

                    // Reduce stock
                    foreach ($order->items as $item) {
                        $item->product->decrement('stock', $item->quantity);
                    }

                    Log::info("Order #{$order->order_number} payment completed via webhook");
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Webhook order update failed: ' . $e->getMessage());
            }
        }
    }

    private function handleCheckoutExpired($session)
    {
        $orderId = $session->metadata->order_id ?? null;
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled'
                ]);
                Log::warning("Order #{$order->order_number} checkout session expired");
            }
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        // You can implement additional logic here if needed
        Log::warning('Payment failed: ' . $paymentIntent->id);
    }

    /**
     * Get Stripe Public Key
     */
    public function getPublicKey()
    {
        return response()->json([
            'publicKey' => config('services.stripe.public_key')
        ]);
    }
}