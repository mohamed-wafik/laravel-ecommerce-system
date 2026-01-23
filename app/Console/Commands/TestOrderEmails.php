<?php

namespace App\Console\Commands;

use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOrderEmails extends Command
{
    protected $signature = 'email:test-order {order? : The ID of the order to test with} {--to= : Override recipient email}';
    protected $description = 'Test order status email notifications';

    public function handle()
    {
        $orderId = $this->argument('order');
        $overrideEmail = $this->option('to');
        
        if ($orderId) {
            $order = Order::with(['user', 'itemOrders.product'])->find($orderId);
            if (!$order) {
                $this->error("Order #{$orderId} not found!");
                return 1;
            }
        } else {
            $order = Order::with(['user', 'itemOrders.product'])
                         ->inRandomOrder()
                         ->first();
            if (!$order) {
                $this->error('No orders found in the database!');
                return 1;
            }
        }

        $this->info("Testing email for Order #{$order->id}...");
        
                if ($overrideEmail) {
                    if (!filter_var($overrideEmail, FILTER_VALIDATE_EMAIL)) {
                        $this->error("Invalid email provided for --to: {$overrideEmail}");
                        return 1;
                    }
                    $this->info("Overriding recipient to: {$overrideEmail}");
                }
        
        try {
            $recipient = $overrideEmail ?? ($order->user->email ?? null);
            if (!$recipient) {
                $this->error('No recipient email available for this order.');
                return 1;
            }

            Mail::to($recipient)
                ->send(new OrderStatusMail($order));
                
            $this->info('✓ Test email sent successfully!');
            $this->info("Recipient: {$recipient}");
            $this->info("Order Status: {$order->status}");
            $this->info("Payment Status: {$order->payment_status}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('× Failed to send test email!');
            $this->error($e->getMessage());
            
            return 1;
        }
    }
}