<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    protected const BRANDS = ['Nike', 'Adidas', 'Zara', 'Samsung', 'Apple', 'IKEA'];

    public function run(): void
    {
        foreach (self::BRANDS as $name) {
            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
            ]);
        }

        // One inactive brand — same reasoning as the inactive category above.
        Brand::create([
            'name' => 'Retired Brand',
            'slug' => 'retired-brand',
            'is_active' => false,
        ]);
    }
}
