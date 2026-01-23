<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;

class PaymentController extends BaseController
{
    /**
     * Create Stripe Checkout Session
     */
    public function createPaymentSession(Order $order)
    {
        try {
            abort_if($order->user_id !== Auth::id(), 403);

            if ($order->payment_status === 'paid') {
                return $this->sendError('Order is already paid', [], 400);
            }

            $order->load('itemOrders.product', 'user');

            if ($order->itemOrders->isEmpty()) {
                return $this->sendError('Order has no items.', [], 400);
            }

            $lineItems = [];

            foreach ($order->itemOrders as $item) {
                if (!$item->product) {
                    return $this->sendError('Product no longer exists.', [], 400);
                }

                if ($item->product->stock < $item->quantity) {
                    return $this->sendError(
                        "Insufficient stock for {$item->product->title}", 
                        [], 
                        400
                    );
                }

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->title,
                            'description' => $item->product->description ?? '',
                            'images' => $item->product->image 
                                ? [url('storage/' . $item->product->image)] 
                                : [],
                        ],
                        'unit_amount' => (int) round($item->unit_price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            if (empty($lineItems)) {
                return $this->sendError('No valid products in the order.', [], 400);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success', ['order' => $order->id]),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                'customer_email' => $order->user->email,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,   
                ],
                'payment_intent_data' => [
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ],
                'expires_at' => now()->addMinutes(30)->timestamp, 
            ]);

            DB::transaction(function () use ($order, $session) {
                $order->update([
                    'payment_session_id' => $session->id,
                    'payment_status' => 'pending',
                ]);
            });

            return $this->sendResponse(
                [
                    'checkout_url' => $session->url,
                    'session_id' => $session->id,
                ],
                'Payment session created successfully.'
            );

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'error' => $e->getError()
            ]);
            
            return $this->sendError(
                'Payment service error. Please try again.', 
                [], 
                500
            );
            
        } catch (\Exception $e) {
            Log::error('Payment Session Error: ' . $e->getMessage(), [
                'order_id' => $order->id
            ]);
            
            return $this->sendError(
                'An error occurred. Please try again.', 
                [], 
                500
            );
        }
    }

    /**
     * Payment Success Redirect (UI ONLY)
     * Stripe Webhook is the authority
     */
    public function handleSuccess(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        
        return redirect(
            config('app.frontend_url') . '/orders/' . $order->id
        );
    }
    /**
     * Payment Cancel Redirect
     */
    public function handleCancel(Order $order)
    {
        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'failed',
            ]);
        }

        return redirect(
            config('app.frontend_url') . '/orders/' . $order->id
        );
    }
    /**
     * Stripe Webhook (FINAL AUTHORITY)
     */
    public function handleWebhook(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');
        
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, 
                $sig_header, 
                $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook error'], 400);
        }

        // Handle the event
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            $order = Order::where('payment_session_id', $session->id)->first();
            
            if ($order) {
                DB::transaction(function () use ($order, $session) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'payment_method' => 'stripe',
                        'transaction_id' => $session->payment_intent,
                        'paid_at' => now(),
                    ]);
                    
                    foreach ($order->itemOrders as $item) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                    
                    // Send confirmation email
                    // Mail::to($order->user)->send(new OrderConfirmed($order));
                });
            }
        }

        return response()->json(['success' => true]);
    }
}