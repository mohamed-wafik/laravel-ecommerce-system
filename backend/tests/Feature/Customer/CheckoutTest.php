<?php

namespace Tests\Feature\Customer;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Concerns\FakesExternalServices;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use FakesExternalServices, InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeExternalServices();
    }

    protected function addToCart(\App\Models\User $user, Product $product, int $qty = 1): void
    {
        $cart = Cart::factory()->create(['user_id' => $user->id, 'status' => 'active']);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $qty,
            'price_snapshot' => $product->price,
        ]);
    }

    public function test_checkout_creates_order_decrements_stock_and_opens_payment_session(): void
    {
        $user = $this->actingAsCustomer();
        $product = Product::factory()->create(['price' => 50, 'stock_quantity' => 10]);
        $this->addToCart($user, $product, 2);
        
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/customer/checkout', [
            'shipping_address_id' => $address->id,
            'delivery_method' => 'standard',
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $response->assertCreated()->assertJsonStructure(['data' => ['order', 'checkout_url', 'checkout_session_id']]);

        $this->assertSame(8, $product->fresh()->stock_quantity);
        // Cart deliberately stays `active` until payment actually succeeds
        // (via webhook) — not converted at order-creation time — so a
        // retried/updated checkout can still find and re-price it.
        $this->assertSame('active', Cart::where('user_id', $user->id)->first()->status);
        $this->assertCount(1, $this->fakePaymentGateway->createdSessions);
    }

    public function test_checkout_with_empty_cart_fails(): void
    {
        $user = $this->actingAsCustomer();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->postJson('/customer/checkout', [
            'shipping_address_id' => $address->id,
            'delivery_method' => 'standard',
            'idempotency_key' => (string) Str::uuid(),
        ])->assertUnprocessable();
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $user = $this->actingAsCustomer();
        $product = Product::factory()->create(['stock_quantity' => 1]);
        $this->addToCart($user, $product, 5);
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->postJson('/customer/checkout', [
            'shipping_address_id' => $address->id,
            'delivery_method' => 'standard',
            'idempotency_key' => (string) Str::uuid(),
        ])->assertUnprocessable();

        // Nothing should have been reserved on a failed checkout.
        $this->assertSame(1, $product->fresh()->stock_quantity);
    }

    public function test_repeating_the_same_idempotency_key_does_not_create_a_second_order(): void
    {
        $user = $this->actingAsCustomer();
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $this->addToCart($user, $product, 1);
        $address = Address::factory()->create(['user_id' => $user->id]);
        $key = (string) Str::uuid();

        $this->postJson('/customer/checkout', [
            'shipping_address_id' => $address->id,
            'delivery_method' => 'standard',
            'idempotency_key' => $key,
        ])->assertCreated();

        $this->assertSame(1, \App\Models\Order::count());

        // Retry with the same key — cart is already converted/empty, but
        // since the order is still pending this should just hand back the
        // existing order rather than erroring or duplicating anything.
        $this->postJson('/customer/checkout', [
            'shipping_address_id' => $address->id,
            'delivery_method' => 'standard',
            'idempotency_key' => $key,
        ])->assertCreated();

        $this->assertSame(1, \App\Models\Order::count());
    }

    public function test_checkout_accepts_an_inline_address_and_saves_it(): void
    {
        $user = $this->actingAsCustomer();
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $this->addToCart($user, $product, 1);

        $response = $this->postJson('/customer/checkout', [
            'shipping_address' => [
                'full_name' => 'Jane Doe',
                'phone' => '0123456789',
                'country' => 'Egypt',
                'city' => 'Cairo',
                'street' => '123 Main St',
            ],
            'delivery_method' => 'standard',
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('addresses', ['user_id' => $user->id, 'city' => 'Cairo']);
    }

    public function test_delivery_fee_is_waived_above_the_free_threshold(): void
    {
        $user = $this->actingAsCustomer();
        app(SettingService::class)->set('free_delivery_threshold', 50);
        app(SettingService::class)->set('delivery_fee_standard', 10);

        $product = Product::factory()->create(['price' => 100, 'stock_quantity' => 10]);
        $this->addToCart($user, $product, 1);
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/customer/checkout', [
            'shipping_address_id' => $address->id,
            'delivery_method' => 'standard',
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $this->assertEquals(0, $response->json('data.order.shipping_fee'));
    }

    public function test_checkout_requires_authentication(): void
    {
        $this->postJson('/customer/checkout', [
            'delivery_method' => 'standard',
            'idempotency_key' => (string) Str::uuid(),
        ])->assertUnauthorized();
    }
}