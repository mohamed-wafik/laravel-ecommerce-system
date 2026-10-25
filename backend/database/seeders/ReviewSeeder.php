<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->inRandomOrder()->limit(15)->get();
        $products = Product::where('is_active', true)->inRandomOrder()->limit(15)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command?->warn('Run UserSeeder and ProductSeeder before ReviewSeeder.');

            return;
        }

        foreach ($products as $product) {
            // 2-4 approved reviews per product — what guests actually see
            // on GET /guest/products/{slug}.
            Review::factory()
                ->approved()
                ->count(fake()->numberBetween(2, 4))
                ->create(['product_id' => $product->id, 'user_id' => $customers->random()->id]);
        }

        // A batch of PENDING reviews across random products — exercises
        // admin's "pending approval" queue (/admin/reviews?is_approved=0).
        foreach ($products->random(min(8, $products->count())) as $product) {
            Review::factory()->create([
                'product_id' => $product->id,
                'user_id' => $customers->random()->id,
                'is_approved' => false,
            ]);
        }
    }
}
