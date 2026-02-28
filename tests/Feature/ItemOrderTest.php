<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_order_can_be_created()
    {
        $itemOrder = ItemOrder::factory()->create();

        $this->assertNotNull($itemOrder->id);
        $this->assertNotNull($itemOrder->order_id);
        $this->assertNotNull($itemOrder->product_id);
        $this->assertGreater($itemOrder->quantity, 0);
        $this->assertGreater($itemOrder->unit_price, 0);
    }

    public function test_item_order_belongs_to_order()
    {
        $order = Order::factory()->create();
        $itemOrder = ItemOrder::factory()->for($order)->create();

        $this->assertEquals($itemOrder->order_id, $order->id);
        $this->assertTrue($itemOrder->order()->exists());
    }

    public function test_item_order_belongs_to_product()
    {
        $product = Product::factory()->create();
        $itemOrder = ItemOrder::factory()->for($product, 'product')->create();

        $this->assertEquals($itemOrder->product_id, $product->id);
        $this->assertTrue($itemOrder->product()->exists());
    }

    public function test_item_order_subtotal_calculation()
    {
        $itemOrder = ItemOrder::factory()->create([
            'quantity' => 5,
            'price' => 20.00,
        ]);

        $expected_total = 5 * 20.00; // 100.00
        $this->assertEquals($expected_total, $itemOrder->total);
    }

    public function test_multiple_items_in_order()
    {
        $order = Order::factory()->create();
        
        $item1 = ItemOrder::factory()->for($order)->create([
            'quantity' => 2,
            'price' => 50.00,
        ]);

        $item2 = ItemOrder::factory()->for($order)->create([
            'quantity' => 3,
            'price' => 30.00,
        ]);

        $item3 = ItemOrder::factory()->for($order)->create([
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $this->assertEquals(3, $order->items()->count());
        
        $total_subtotal = $item1->total + $item2->total + $item3->total;
        $this->assertEquals(310.00, $total_subtotal); // (2*50) + (3*30) + (1*100)
    }

    public function test_item_order_decimal_fields_are_properly_cast()
    {
        $itemOrder = ItemOrder::factory()->create([
            'price' => 99.99,
            'total' => 199.98,
        ]);

        $this->assertIsFloat($itemOrder->price);
        $this->assertIsFloat($itemOrder->total);
    }

    public function test_different_products_in_same_order()
    {
        $order = Order::factory()->create();
        
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product3 = Product::factory()->create();

        $item1 = ItemOrder::factory()->for($order)->for($product1, 'product')->create();
        $item2 = ItemOrder::factory()->for($order)->for($product2, 'product')->create();
        $item3 = ItemOrder::factory()->for($order)->for($product3, 'product')->create();

        $this->assertEquals(3, $order->items()->count());
        $this->assertNotEquals($item1->product_id, $item2->product_id);
        $this->assertNotEquals($item2->product_id, $item3->product_id);
    }
}