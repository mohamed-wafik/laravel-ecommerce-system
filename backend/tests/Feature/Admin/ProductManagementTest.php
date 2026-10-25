<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\FakesExternalServices;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use FakesExternalServices, InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeExternalServices();
        $this->actingAsAdmin();
    }

    public function test_create_product_with_images_and_variants_in_one_request(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $response = $this->postJson('/admin/products', [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Running Shoes',
            'price' => 59.99,
            'sku' => 'SHOE-001',
            'stock_quantity' => 40,
            'images' => [UploadedFile::fake()->image('shoe1.png'), UploadedFile::fake()->image('shoe2.png')],
            'variants' => [
                ['color' => 'Red', 'size' => '42', 'sku' => 'SHOE-001-42', 'stock_quantity' => 10],
                ['color' => 'Red', 'size' => '43', 'sku' => 'SHOE-001-43', 'stock_quantity' => 15],
            ],
        ]);

        $response->assertCreated();
        $product = Product::where('sku', 'SHOE-001')->firstOrFail();

        $this->assertCount(2, $product->images);
        $this->assertCount(2, $product->variants);
        $this->assertCount(2, $this->fakeCloudinary->uploaded);
    }

    public function test_replacing_images_on_update_destroys_old_cloudinary_assets(): void
    {
        $product = Product::factory()->create();
        $product->images()->create(['url' => 'old.jpg', 'public_id' => 'products/old-1', 'is_primary' => true]);

        $this->patchJson("/admin/products/{$product->id}", [
            'images' => [UploadedFile::fake()->image('new.png')],
        ])->assertOk();

        $this->assertContains('products/old-1', $this->fakeCloudinary->destroyed);
        $this->assertCount(1, $product->fresh()->images);
    }

    public function test_adjust_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $this->postJson("/admin/products/{$product->id}/adjust-stock", ['delta' => -3])
            ->assertOk()
            ->assertJsonPath('data.stock_quantity', 7);
    }

    public function test_adjust_stock_never_goes_negative(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 2]);

        $this->postJson("/admin/products/{$product->id}/adjust-stock", ['delta' => -10])
            ->assertOk()
            ->assertJsonPath('data.stock_quantity', 0);
    }

    public function test_low_stock_endpoint_only_returns_active_low_stock_products(): void
    {
        Product::factory()->create(['stock_quantity' => 2, 'is_active' => true]);
        Product::factory()->create(['stock_quantity' => 100, 'is_active' => true]);
        Product::factory()->create(['stock_quantity' => 1, 'is_active' => false]);

        $response = $this->getJson('/admin/products-low-stock?threshold=5');

        $this->assertCount(1, $response->json('data.items'));
    }

    public function test_delete_removes_all_product_images_from_cloudinary(): void
    {
        $product = Product::factory()->create();
        $product->images()->create(['url' => 'a.jpg', 'public_id' => 'products/a']);
        $product->images()->create(['url' => 'b.jpg', 'public_id' => 'products/b']);

        $this->deleteJson("/admin/products/{$product->id}")->assertNoContent();

        $this->assertContains('products/a', $this->fakeCloudinary->destroyed);
        $this->assertContains('products/b', $this->fakeCloudinary->destroyed);
    }
}
