<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * root name => [child names...]. Real-looking store taxonomy so
     * guest browsing / admin category-tree endpoints have something
     * meaningful to render, including one INACTIVE root to verify the
     * guest catalog correctly excludes it.
     */
    protected const TREE = [
        'Men' => ['Shirts', 'Shoes', 'Accessories'],
        'Women' => ['Dresses', 'Shoes', 'Bags'],
        'Electronics' => ['Phones', 'Laptops', 'Headphones'],
        'Home & Kitchen' => ['Furniture', 'Cookware'],
    ];

    public function run(): void
    {
        $sort = 0;

        foreach (self::TREE as $rootName => $children) {
            $root = Category::create([
                'name' => $rootName,
                'slug' => Str::slug($rootName),
                'is_active' => true,
                'sort_order' => $sort++,
            ]);

            foreach ($children as $i => $childName) {
                Category::create([
                    'parent_id' => $root->id,
                    'name' => $childName,
                    'slug' => Str::slug($rootName.'-'.$childName),
                    'is_active' => true,
                    'sort_order' => $i,
                ]);
            }
        }

        // Inactive root — guest catalog endpoints must never return this.
        Category::create([
            'name' => 'Discontinued Line',
            'slug' => 'discontinued-line',
            'is_active' => false,
            'sort_order' => 99,
        ]);
    }
}
