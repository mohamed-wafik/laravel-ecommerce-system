<?php

namespace Tests\Feature\Customer;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class CartTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    public function test_add_item_to_own_cart(): void
    {
        $this->actingAsCustomer();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson('/customer/cart/items', ['product_id' => $product->id, 'quantity' => 2])
            ->assertOk();

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_cart_requires_authentication(): void
    {
        $this->getJson('/customer/cart')->assertUnauthorized();
    }

    public function test_remove_item(): void
    {
        $this->actingAsCustomer();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $addResponse = $this->postJson('/customer/cart/items', ['product_id' => $product->id, 'quantity' => 1]);
        $itemId = $addResponse->json('data.items.0.id');

        $this->deleteJson("/customer/cart/items/{$itemId}")->assertOk();
        $this->assertDatabaseMissing('cart_items', ['id' => $itemId]);
    }
}
