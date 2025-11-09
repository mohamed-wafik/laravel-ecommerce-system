<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment session for an order
     */
    public function createPaymentSession(Order $order)
    {
        try {
            $lineItems = [];

            foreach ($order->itemOrders as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->title,
                            'description' => $item->product->description,
                            'images' => [$item->product->image],
                        ],
                        'unit_amount' => $item->unit_price * 100, // Stripe expects amounts in cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success', ['order' => $order->id]),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                'customer_email' => $order->user->email,
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            $order->update([
                'payment_tsession_id' => $session->id,
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'url' => $session->url,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function handleSuccess(Request $request, Order $order)
    {
        try {
            $session = \Stripe\Checkout\Session::retrieve($order->payment_tsession_id);
            
            if ($session->payment_status === 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_id' => $session->payment_intent,
                    'status' => 'processing',
                ]);

                // Send order confirmation email
                Mail::to($order->user->email)->send(new OrderStatusMail($order));

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment successful! Your order is being processed.');
            }

            return redirect()->route('orders.show', $order)
                ->with('error', 'Payment verification failed. Please contact support.');

        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'There was an error processing your payment.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function handleCancel(Order $order)
    {
        $order->update([
            'payment_status' => 'failed',
        ]);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment was cancelled. Please try again.');
    }

    /**
     * Retry a failed payment
     */
    public function retryPayment(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'This order has already been paid.');
        }

        try {
            return $this->createPaymentSession($order);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Could not create new payment session.',
            ], 500);
        }
    }

    /**
     * Webhook handler for Stripe events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $order = Order::where('payment_id', $paymentIntent->id)->first();
                    
                    if ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing',
                        ]);
                        
                        Mail::to($order->user->email)->send(new OrderStatusMail($order));
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $order = Order::where('payment_id', $paymentIntent->id)->first();
                    
                    if ($order) {
                        $order->update([
                            'payment_status' => 'failed',
                        ]);
                        
                        Mail::to($order->user->email)->send(new OrderStatusMail($order));
                    }
                    break;
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}