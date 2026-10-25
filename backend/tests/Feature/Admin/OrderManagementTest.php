<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_valid_status_transition_succeeds(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $this->patchJson("/admin/orders/{$order->id}/status", ['status' => 'processing'])
            ->assertOk()
            ->assertJsonPath('data.status', 'processing');
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        // pending -> delivered is not a legal direct transition
        $this->patchJson("/admin/orders/{$order->id}/status", ['status' => 'delivered'])
            ->assertUnprocessable();

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_cancelling_an_order_restocks_its_items(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $order = Order::factory()->create(['status' => 'processing']);
        OrderItem::factory()->create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 3]);

        $this->patchJson("/admin/orders/{$order->id}/status", ['status' => 'cancelled'])->assertOk();

        $this->assertSame(8, $product->fresh()->stock_quantity);
    }

    public function test_stats_endpoint_returns_counts_by_status(): void
    {
        Order::factory()->create(['status' => 'pending']);
        Order::factory()->create(['status' => 'paid']);
        Order::factory()->create(['status' => 'paid']);

        $response = $this->getJson('/admin/orders/stats');

        $response->assertOk()
            ->assertJsonPath('data.pending', 1)
            ->assertJsonPath('data.paid', 2);
    }
}
