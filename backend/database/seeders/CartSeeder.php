<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->inRandomOrder()->limit(5)->get();

        if (! $customer || $products->isEmpty()) {
            $this->command?->warn('Run UserSeeder and ProductSeeder before CartSeeder.');

            return;
        }

        // The known customer's active cart, pre-loaded — hit GET
        // /customer/cart or POST /customer/checkout immediately without
        // adding items manually first.
        $cart = Cart::create(['user_id' => $customer->id, 'status' => 'active']);

        foreach ($products->take(2) as $product) {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => fake()->numberBetween(1, 2),
                'price_snapshot' => $product->price,
            ]);
        }
    }
}