<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\ItemOrder;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderAndPaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_order_creation_flow()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100.00, 'stock' => 10]);

        // Add product to cart
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $orderData = [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '1234567890',
            'shipping_address' => '123 Main St',
            'city' => 'Cairo',
            'postal_code' => '12345',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $orderData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
        ]);
    }

    public function test_order_with_cod_payment_reduces_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50.00, 'stock' => 10]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $orderData = [
            'customer_name' => 'Jane Doe',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '9876543210',
            'shipping_address' => '456 Oak Ave',
            'city' => 'Alexandria',
            'postal_code' => '54321',
            'shipping_method' => 'express',
            'payment_method' => 'cod',
        ];

        $this->actingAs($user)->postJson('/api/orders', $orderData);

        $product->refresh();
        $this->assertEquals(8, $product->stock); // 10 - 2
    }

    public function test_order_cart_is_cleared_after_creation()
    {
        $user = User::factory()->create();
        Product::factory()->create(['price' => 75.00, 'stock' => 5]);

        $cart = Cart::factory()->for($user)->count(3)->create();

        $orderData = [
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '5555555555',
            'shipping_address' => '789 Elm St',
            'city' => 'Giza',
            'postal_code' => '99999',
            'shipping_method' => 'pickup',
            'payment_method' => 'wallet',
        ];

        $this->actingAs($user)->postJson('/api/orders', $orderData);

        $this->assertEquals(0, $user->carts()->count());
    }

    public function test_order_items_are_created_correctly()
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 100.00, 'stock' => 10]);
        $product2 = Product::factory()->create(['price' => 50.00, 'stock' => 10]);

        Cart::create(['user_id' => $user->id, 'product_id' => $product1->id, 'quantity' => 2]);
        Cart::create(['user_id' => $user->id, 'product_id' => $product2->id, 'quantity' => 3]);

        $orderData = [
            'customer_name' => 'Item Test',
            'customer_email' => 'itemtest@example.com',
            'customer_phone' => '1111111111',
            'shipping_address' => '111 Pine St',
            'city' => 'Cairo',
            'postal_code' => '11111',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $orderData);
        $order = Order::first();

        $this->assertEquals(2, $order->items()->count());
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 100.00,
            'total' => 200.00,
        ]);
    }

    public function test_order_payment_status_update()
    {
        $order = Order::factory()->create(['payment_status' => 'pending']);

        $this->patchJson("/api/orders/{$order->id}/payment-status", [
            'payment_status' => 'paid'
        ]);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->order_status);
    }

    public function test_order_retrieval_by_user()
    {
        $user = User::factory()->create();
        $orders = Order::factory(3)->for($user)->create();

        $response = $this->actingAs($user)->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_order_retrieval_by_order_number()
    {
        $order = Order::factory()->create();

        $response = $this->getJson("/api/orders/{$order->order_number}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.order_number', $order->order_number);
    }

    public function test_order_with_shipping_costs()
    {
        $user = User::factory()->create();
        Product::factory()->create(['price' => 100.00, 'stock' => 5]);

        Cart::factory()->for($user)->create(['quantity' => 1]);

        $orderData = [
            'customer_name' => 'Shipping Test',
            'customer_email' => 'shipping@example.com',
            'customer_phone' => '2222222222',
            'shipping_address' => '222 Maple Dr',
            'city' => 'Cairo',
            'postal_code' => '22222',
            'shipping_method' => 'express',
            'payment_method' => 'card',
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $orderData);
        $order = Order::first();

        // Express shipping should be 60 EGP
        $this->assertEquals(60, $order->shipping_cost);
    }

    public function test_order_tax_calculation()
    {
        $user = User::factory()->create();
        Product::factory()->create(['price' => 100.00, 'stock' => 5]);

        Cart::factory()->for($user)->create(['quantity' => 1]);

        $orderData = [
            'customer_name' => 'Tax Test',
            'customer_email' => 'tax@example.com',
            'customer_phone' => '3333333333',
            'shipping_address' => '333 Cedar Ln',
            'city' => 'Cairo',
            'postal_code' => '33333',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ];

        $this->actingAs($user)->postJson('/api/orders', $orderData);
        $order = Order::first();

        // Tax should be 14% of subtotal
        $expected_tax = round(100 * 0.14, 2);
        $this->assertEquals($expected_tax, $order->tax);
    }

    public function test_order_total_includes_all_components()
    {
        $user = User::factory()->create();
        Product::factory()->create(['price' => 100.00, 'stock' => 5]);

        Cart::factory()->for($user)->create(['quantity' => 1]);

        $orderData = [
            'customer_name' => 'Total Test',
            'customer_email' => 'total@example.com',
            'customer_phone' => '4444444444',
            'shipping_address' => '444 Birch Ave',
            'city' => 'Cairo',
            'postal_code' => '44444',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ];

        $this->actingAs($user)->postJson('/api/orders', $orderData);
        $order = Order::first();

        // Total = subtotal + shipping + tax
        $expected_total = 100 + 30 + round(100 * 0.14, 2);
        $this->assertEquals($expected_total, $order->total);
    }

    public function test_order_cannot_be_created_with_empty_cart()
    {
        $user = User::factory()->create();

        $orderData = [
            'customer_name' => 'Empty Cart Test',
            'customer_email' => 'emptycart@example.com',
            'customer_phone' => '5555555555',
            'shipping_address' => '555 Spruce Pl',
            'city' => 'Cairo',
            'postal_code' => '55555',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $orderData);
        $response->assertStatus(400);
    }

    public function test_order_cannot_be_created_with_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50.00, 'stock' => 2]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $orderData = [
            'customer_name' => 'Insufficient Stock',
            'customer_email' => 'nostock@example.com',
            'customer_phone' => '6666666666',
            'shipping_address' => '666 Willow Rd',
            'city' => 'Cairo',
            'postal_code' => '66666',
            'shipping_method' => 'standard',
            'payment_method' => 'card',
        ];

        $response = $this->actingAs($user)->postJson('/api/orders', $orderData);
        $response->assertStatus(400);
    }
}
