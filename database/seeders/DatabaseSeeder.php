<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\ItemOrder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1️⃣ Create Categories & Products ---
        Category::factory(5)
            ->create()
            ->each(function ($category) {
                Product::factory(10)->create([
                    'category_id' => $category->id,
                ]);
            });

        // --- 2️⃣ Create Users ---
        // One known user for testing login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Additional fake users
        $users = User::factory(20)->create();

        // --- 3️⃣ Create Orders & ItemOrders ---
        $products = Product::all();

        foreach ($users as $user) {
            $ordersCount = rand(1, 3);

            for ($i = 0; $i < $ordersCount; $i++) {
                $order = Order::factory()->create([
                    'user_id' => $user->id,
                    'total_amount' => 0,
                    'status' => fake()->randomElement(['pending', 'shipped', 'delivered', 'cancelled']),
                    'payment_id' => fake()->boolean(60) ? fake()->uuid() : null,
                ]);

                $selectedProducts = $products->random(rand(1, 5));
                $total = 0;

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 5);
                    $unitPrice = $product->price;
                    $subtotal = round($quantity * $unitPrice, 2);

                    ItemOrder::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    $total += $subtotal;
                }

                $order->update(['total_amount' => round($total, 2)]);
            }
        }

        $this->command->info('✅ Database seeded successfully with fake data.');
    }
}