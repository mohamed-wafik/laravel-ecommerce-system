<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'color' => fake()->safeColorName(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'sku' => strtoupper(Str::random(10)),
            'stock_quantity' => 10,
        ];
    }
}
