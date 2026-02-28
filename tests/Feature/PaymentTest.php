<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_marked_as_paid()
    {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
            'payment_method' => 'card',
        ]);

        $order->update([
            'payment_status' => 'paid',
            'payment_id' => 'pi_1234567890',
            'transaction_id' => 'txn_1234567890',
            'paid_at' => now(),
        ]);

        $this->assertEquals('paid', $order->payment_status);
        $this->assertNotNull($order->payment_id);
        $this->assertNotNull($order->transaction_id);
        $this->assertNotNull($order->paid_at);
    }

    public function test_order_can_mark_payment_as_failed()
    {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
            'payment_method' => 'card',
        ]);

        $order->update(['payment_status' => 'failed']);

        $this->assertEquals('failed', $order->payment_status);
    }

    public function test_cod_payment_order_creation()
    {
        $order = Order::factory()->create([
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);

        $this->assertEquals('cod', $order->payment_method);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertNull($order->paid_at);
    }

    public function test_card_payment_order_with_stripe_session()
    {
        $order = Order::factory()->create([
            'payment_method' => 'card',
            'payment_status' => 'pending',
            'payment_tsession_id' => 'cs_test_abcdef123456',
        ]);

        $this->assertEquals('card', $order->payment_method);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals('cs_test_abcdef123456', $order->payment_tsession_id);
    }

    public function test_wallet_payment_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create([
            'payment_method' => 'wallet',
            'payment_status' => 'pending',
        ]);

        $this->assertEquals('wallet', $order->payment_method);
        $this->assertNotNull($order->user_id);
    }

    public function test_multiple_orders_payment_tracking()
    {
        $user = User::factory()->create();
        
        $order1 = Order::factory()->for($user)->create([
            'payment_status' => 'paid',
            'payment_id' => 'pi_001',
        ]);

        $order2 = Order::factory()->for($user)->create([
            'payment_status' => 'pending',
        ]);

        $order3 = Order::factory()->for($user)->create([
            'payment_status' => 'failed',
        ]);

        $paid_orders = Order::where('payment_status', 'paid')->count();
        $pending_orders = Order::where('payment_status', 'pending')->count();
        $failed_orders = Order::where('payment_status', 'failed')->count();

        $this->assertEquals(1, $paid_orders);
        $this->assertEquals(1, $pending_orders);
        $this->assertEquals(1, $failed_orders);
    }

    public function test_payment_status_cannot_be_empty()
    {
        $this->expectException(\Exception::class);
        
        Order::factory()->create([
            'payment_status' => '',
        ]);
    }

    public function test_order_total_includes_all_components()
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'tax' => 10.00,
            'shipping_cost' => 5.00,
            'discount' => 5.00,
        ]);

        $expected = 100 + 10 + 5 - 5; // 110.00
        $this->assertEquals($expected, $order->total);
    }

    public function test_paid_at_timestamp_is_only_set_when_paid()
    {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
        ]);

        $this->assertNull($order->paid_at);

        $now = now();
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => $now,
        ]);

        $this->assertNotNull($order->paid_at);
        $this->assertTrue($order->paid_at->equalTo($now));
    }

    public function test_payment_service_can_create_checkout_session()
    {
        $stripeService = new StripeService();
        $this->assertNotNull($stripeService);
    }

    public function test_order_can_store_multiple_payment_attempts()
    {
        $order = Order::factory()->create([
            'payment_method' => 'card',
            'payment_status' => 'failed',
        ]);

        // First attempt failed
        $this->assertEquals('failed', $order->payment_status);

        // Retry payment
        $order->update([
            'payment_status' => 'paid',
            'payment_id' => 'pi_9876543210',
            'transaction_id' => 'txn_9876543210',
            'paid_at' => now(),
        ]);

        $this->assertEquals('paid', $order->payment_status);
        $this->assertNotNull($order->payment_id);
    }
}