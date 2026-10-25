<?php

namespace Tests\Feature\Guest;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_only_returns_active_ones(): void
    {
        Category::factory()->create(['name' => 'Visible']);
        Category::factory()->inactive()->create(['name' => 'Hidden']);

        $response = $this->getJson('/guest/categories');

        $names = collect($response->json('data'))->pluck('name');
        $this->assertTrue($names->contains('Visible'));
        $this->assertFalse($names->contains('Hidden'));
    }

    public function test_products_can_be_filtered_by_price_range(): void
    {
        Product::factory()->create(['price' => 10, 'name' => 'Cheap']);
        Product::factory()->create(['price' => 500, 'name' => 'Expensive']);

        $response = $this->getJson('/guest/products?min_price=100&max_price=1000');

        $names = collect($response->json('data.items'))->pluck('name');
        $this->assertTrue($names->contains('Expensive'));
        $this->assertFalse($names->contains('Cheap'));
    }

    public function test_inactive_products_are_never_returned(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->getJson('/guest/products')
            ->assertJsonMissing(['id' => $product->id]);
    }

    public function test_product_detail_includes_reviews_and_related_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'slug' => 'my-product']);
        Product::factory()->create(['category_id' => $category->id]); // related
        Review::factory()->approved()->create(['product_id' => $product->id]);
        Review::factory()->create(['product_id' => $product->id, 'is_approved' => false]); // hidden

        $response = $this->getJson('/guest/products/my-product');

        $response->assertOk();
        $this->assertCount(1, $response->json('data.product.reviews'));
        $this->assertCount(1, $response->json('data.related'));
    }

    public function test_search_returns_matching_products_and_categories(): void
    {
        Product::factory()->create(['name' => 'Blue Sneakers']);
        Category::factory()->create(['name' => 'Sneakers Category']);

        $response = $this->getJson('/guest/search?q=Sneakers');

        $this->assertNotEmpty($response->json('data.products'));
        $this->assertNotEmpty($response->json('data.categories'));
    }

    public function test_brands_only_returns_active_ones(): void
    {
        Brand::factory()->create(['name' => 'Active Brand', 'is_active' => true]);
        Brand::factory()->create(['name' => 'Inactive Brand', 'is_active' => false]);

        $names = collect($this->getJson('/guest/brands')->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Active Brand'));
        $this->assertFalse($names->contains('Inactive Brand'));
    }
}
