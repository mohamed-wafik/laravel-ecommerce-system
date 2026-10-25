<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Deliberately covers every stock/status state the API branches on, so
 * guest/admin product endpoints all have something real to return:
 *   - plenty of normal in-stock active products (the common case)
 *   - a few LOW stock (<5) — for admin's /products-low-stock
 *   - a couple OUT of stock (0) — guest catalog must exclude these
 *   - a couple INACTIVE — guest catalog must exclude these too
 *   - some WITH variants (color/size), some without
 *   - some WITH multiple images, one WITHOUT any
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $leafCategories = Category::whereNotNull('parent_id')->get();
        $brands = Brand::where('is_active', true)->get();

        if ($leafCategories->isEmpty() || $brands->isEmpty()) {
            $this->command?->warn('Run CategorySeeder and BrandSeeder before ProductSeeder.');

            return;
        }

        // The bulk: normal, in-stock, active, browsable products.
        Product::factory()
            ->count(30)
            ->state(fn () => [
                'category_id' => $leafCategories->random()->id,
                'brand_id' => $brands->random()->id,
            ])
            ->create()
            ->each(fn (Product $product, int $i) => $this->attachImages($product, $i % 5 !== 0)); // ~80% get images

        // Low stock — for admin's low-stock dashboard/endpoint.
        Product::factory()
            ->count(4)
            ->state(fn () => [
                'category_id' => $leafCategories->random()->id,
                'brand_id' => $brands->random()->id,
                'stock_quantity' => fake()->numberBetween(1, 4),
            ])
            ->create()
            ->each(fn (Product $product) => $this->attachImages($product));

        // Out of stock — guest catalog must exclude these (Product::inStock()).
        Product::factory()
            ->outOfStock()
            ->count(3)
            ->state(fn () => [
                'category_id' => $leafCategories->random()->id,
                'brand_id' => $brands->random()->id,
            ])
            ->create()
            ->each(fn (Product $product) => $this->attachImages($product));

        // Inactive — guest catalog must exclude these too (Product::active()).
        Product::factory()
            ->inactive()
            ->count(3)
            ->state(fn () => [
                'category_id' => $leafCategories->random()->id,
                'brand_id' => $brands->random()->id,
            ])
            ->create()
            ->each(fn (Product $product) => $this->attachImages($product));

        // A handful with real variants (color/size), for cart/checkout
        // testing against variant-priced/variant-stocked items.
        Product::factory()
            ->count(5)
            ->state(fn () => [
                'category_id' => $leafCategories->random()->id,
                'brand_id' => $brands->random()->id,
                'name' => 'Variant Test — '.fake()->words(2, true),
            ])
            ->create()
            ->each(function (Product $product) {
                $this->attachImages($product);

                foreach (['S', 'M', 'L'] as $size) {
                    $product->variants()->create([
                        'color' => fake()->safeColorName(),
                        'size' => $size,
                        'sku' => strtoupper($product->sku.'-'.$size),
                        'stock_quantity' => fake()->numberBetween(0, 20),
                    ]);
                }
            });

        // One product with a fixed, known slug — handy for manually
        // hitting GET /guest/products/{slug} without looking an id up first.
        $known = Product::factory()->create([
            'category_id' => $leafCategories->first()->id,
            'brand_id' => $brands->first()->id,
            'name' => 'Sample Product',
            'slug' => 'sample-product',
            'sku' => 'SAMPLE-001',
            'price' => 49.99,
            'stock_quantity' => 25,
        ]);
        $this->attachImages($known);
    }

    protected function attachImages(Product $product, bool $withImages = true): void
    {
        if (! $withImages) {
            return;
        }

        $count = fake()->numberBetween(1, 3);

        for ($i = 0; $i < $count; $i++) {
            $product->images()->create([
                'url' => "https://picsum.photos/seed/{$product->id}-{$i}/800/800",
                'public_id' => 'products/seed-'.Str::random(10),
                'is_primary' => $i === 0,
                'sort_order' => $i,
            ]);
        }
    }
}
