<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\ItemOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_created_with_factory()
    {
        $order = Order::factory()->create();

        $this->assertNotNull($order->id);
        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('ORD-', $order->order_number);
        $this->assertNotNull($order->user_id);
        $this->assertNotNull($order->customer_name);
        $this->assertNotNull($order->customer_email);
        $this->assertNotNull($order->total);
        $this->assertGreater($order->total, 0);
    }

    public function test_order_number_is_auto_generated()
    {
        $order1 = Order::factory()->create();
        $order2 = Order::factory()->create();

        $this->assertNotEquals($order1->order_number, $order2->order_number);
        $this->assertStringStartsWith('ORD-', $order1->order_number);
        $this->assertStringStartsWith('ORD-', $order2->order_number);
    }

    public function test_order_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create();

        $this->assertEquals($order->user_id, $user->id);
        $this->assertTrue($order->user()->exists());
        $this->assertEquals($order->user->id, $user->id);
    }

    public function test_order_has_many_items()
    {
        $order = Order::factory()->create();
        $items = ItemOrder::factory(3)->for($order)->create();

        $this->assertEquals(3, $order->items()->count());
        $this->assertEquals($items[0]->id, $order->items()->first()->id);
    }

    public function test_order_total_calculation()
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'discount' => 10.00,
            'tax' => 9.00,
            'shipping_cost' => 5.00,
        ]);

        $expected_total = 100.00 - 10.00 + 9.00 + 5.00; // 104.00
        $this->assertEquals($expected_total, $order->total);
    }

    public function test_order_payment_status_transitions()
    {
        $order = Order::factory()->create(['payment_status' => 'pending']);
        
        $this->assertEquals('pending', $order->payment_status);

        $order->update(['payment_status' => 'paid', 'paid_at' => now()]);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertNotNull($order->paid_at);

        $order->update(['payment_status' => 'failed']);
        $this->assertEquals('failed', $order->payment_status);
    }

    public function test_order_status_transitions()
    {
        $order = Order::factory()->create(['order_status' => 'pending']);

        $this->assertEquals('pending', $order->order_status);

        $order->update(['order_status' => 'processing']);
        $this->assertEquals('processing', $order->order_status);

        $order->update(['order_status' => 'shipped']);
        $this->assertEquals('shipped', $order->order_status);

        $order->update(['order_status' => 'delivered']);
        $this->assertEquals('delivered', $order->order_status);
    }

    public function test_order_with_all_payment_methods()
    {
        $payment_methods = ['cod', 'card', 'wallet'];

        foreach ($payment_methods as $method) {
            $order = Order::factory()->create(['payment_method' => $method]);
            $this->assertEquals($method, $order->payment_method);
        }
    }

    public function test_order_with_all_shipping_methods()
    {
        $shipping_methods = ['standard', 'express', 'pickup'];

        foreach ($shipping_methods as $method) {
            $order = Order::factory()->create(['shipping_method' => $method]);
            $this->assertEquals($method, $order->shipping_method);
        }
    }

    public function test_order_decimal_fields_are_properly_cast()
    {
        $order = Order::factory()->create([
            'subtotal' => 99.99,
            'tax' => 9.99,
            'discount' => 5.00,
            'shipping_cost' => 10.50,
        ]);

        $this->assertIsFloat($order->subtotal);
        $this->assertIsFloat($order->tax);
        $this->assertIsFloat($order->discount);
        $this->assertIsFloat($order->shipping_cost);
    }

    public function test_order_timestamps_exist()
    {
        $order = Order::factory()->create();

        $this->assertNotNull($order->created_at);
        $this->assertNotNull($order->updated_at);
    }

    public function test_order_with_stripe_session_id()
    {
        $order = Order::factory()->create([
            'payment_tsession_id' => 'cs_test_1234567890',
            'payment_method' => 'card',
        ]);

        $this->assertEquals('cs_test_1234567890', $order->payment_tsession_id);
        $this->assertEquals('card', $order->payment_method);
    }

    public function test_order_with_transaction_id()
    {
        $order = Order::factory()->create([
            'transaction_id' => 'txn_1234567890',
            'payment_status' => 'paid',
        ]);

        $this->assertEquals('txn_1234567890', $order->transaction_id);
    }
}