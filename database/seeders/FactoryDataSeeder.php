<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\ItemOrder;
use App\Models\Cart;
use App\Models\Review;

class FactoryDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n🌱 Starting Factory Data Generation...\n\n";

        // Create 10 Users
        echo "📝 Creating 10 Users...\n";
        $users = User::factory(10)->create();
        echo "✅ Created 10 Users\n\n";

        // Create 5 Categories
        echo "📂 Creating 5 Categories...\n";
        $categories = Category::factory(5)->create();
        echo "✅ Created 5 Categories\n\n";

        // Create 50 Products
        echo "🛍️ Creating 50 Products...\n";
        $products = Product::factory(50)
            ->recycle($categories)
            ->create();
        echo "✅ Created 50 Products\n\n";

        // Create 20 Orders with items for random users
        echo "📦 Creating 20 Orders with items...\n";
        foreach ($users->random(8) as $user) {
            $orders = Order::factory(rand(2, 4))
                ->for($user)
                ->create();
            
            foreach ($orders as $order) {
                ItemOrder::factory(rand(1, 5))
                    ->for($order)
                    ->recycle($products)
                    ->create();
            }
        }
        echo "✅ Created 20 Orders with items\n\n";

        // Create Carts for users
        echo "🛒 Creating Shopping Carts...\n";
        foreach ($users->random(5) as $user) {
            Cart::factory(rand(1, 3))
                ->for($user)
                ->recycle($products)
                ->create();
        }
        echo "✅ Created Shopping Carts\n\n";

        // Create Reviews
        echo "⭐ Creating 50 Reviews...\n";
        Review::factory(50)
            ->recycle($users)
            ->recycle($products)
            ->create();
        echo "✅ Created 50 Reviews\n\n";

        // Summary
        echo "=" . str_repeat("=", 50) . "\n";
        echo "📊 FACTORY DATA GENERATION COMPLETE!\n";
        echo "=" . str_repeat("=", 50) . "\n";
        echo "📈 Summary:\n";
        echo "   • Users: " . User::count() . "\n";
        echo "   • Categories: " . Category::count() . "\n";
        echo "   • Products: " . Product::count() . "\n";
        echo "   • Orders: " . Order::count() . "\n";
        echo "   • Order Items: " . ItemOrder::count() . "\n";
        echo "   • Cart Items: " . Cart::count() . "\n";
        echo "   • Reviews: " . Review::count() . "\n";
        echo "=" . str_repeat("=", 50) . "\n\n";
    }
}