<?php

// namespace Tests\Feature\Guest;

// use App\Models\Product;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Tests\TestCase;

// class CartTest extends TestCase
// {
//     use RefreshDatabase;

//     public function test_cart_endpoints_require_a_guest_token(): void
//     {
//         $this->getJson('/guest/cart')->assertUnprocessable();
//     }

//     public function test_token_endpoint_issues_a_usable_token(): void
//     {
//         $token = $this->postJson('/guest/cart/token')->json('data.guest_token');

//         $this->assertNotEmpty($token);

//         $this->withHeader('X-Guest-Token', $token)
//             ->getJson('/guest/cart')
//             ->assertOk();
//     }

//     public function test_add_item_creates_cart_and_item(): void
//     {
//         $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 25]);
//         $token = $this->postJson('/guest/cart/token')->json('data.guest_token');

//         $response = $this->withHeader('X-Guest-Token', $token)->postJson('/guest/cart/items', [
//             'product_id' => $product->id,
//             'quantity' => 2,
//         ]);

//         $response->assertOk();
//         $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
//     }

//     public function test_add_item_fails_when_stock_is_insufficient(): void
//     {
//         $product = Product::factory()->create(['stock_quantity' => 1]);
//         $token = $this->postJson('/guest/cart/token')->json('data.guest_token');

//         $this->withHeader('X-Guest-Token', $token)->postJson('/guest/cart/items', [
//             'product_id' => $product->id,
//             'quantity' => 5,
//         ])->assertUnprocessable();
//     }

//     public function test_updating_quantity_to_zero_removes_the_item(): void
//     {
//         $product = Product::factory()->create(['stock_quantity' => 10]);
//         $token = $this->postJson('/guest/cart/token')->json('data.guest_token');

//         $addResponse = $this->withHeader('X-Guest-Token', $token)->postJson('/guest/cart/items', [
//             'product_id' => $product->id,
//             'quantity' => 1,
//         ]);
//         $itemId = $addResponse->json('data.items.0.id');

//         $this->withHeader('X-Guest-Token', $token)
//             ->patchJson("/guest/cart/items/{$itemId}", ['quantity' => 0])
//             ->assertOk();

//         $this->assertDatabaseMissing('cart_items', ['id' => $itemId]);
//     }
// }