<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\FakesExternalServices;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use FakesExternalServices, InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeExternalServices();
        $this->actingAsAdmin();
    }

    public function test_create_category_with_image_uploads_to_cloudinary(): void
    {
        $response = $this->postJson('/admin/categories', [
            'name' => 'Shoes',
            'image' => UploadedFile::fake()->image('shoes.png'),
        ]);

        $response->assertCreated();
        $this->assertNotEmpty($this->fakeCloudinary->uploaded);
        $this->assertDatabaseHas('categories', ['name' => 'Shoes']);
        $this->assertNotNull($response->json('data.image_public_id'));
    }

    public function test_replacing_image_deletes_the_old_cloudinary_asset(): void
    {
        $category = Category::factory()->create(['image_public_id' => 'categories/old-asset']);

        $this->patchJson("/admin/categories/{$category->id}", [
            'image' => UploadedFile::fake()->image('new.png'),
        ])->assertOk();

        $this->assertContains('categories/old-asset', $this->fakeCloudinary->destroyed);
    }

    public function test_update_without_a_new_file_leaves_existing_image_untouched(): void
    {
        $category = Category::factory()->create([
            'image_url' => 'https://res.cloudinary.com/fake/existing.jpg',
            'image_public_id' => 'categories/existing',
        ]);

        $this->patchJson("/admin/categories/{$category->id}", ['name' => 'Renamed'])->assertOk();

        $this->assertEmpty($this->fakeCloudinary->destroyed);
        $this->assertSame('categories/existing', $category->fresh()->image_public_id);
    }

    public function test_tree_endpoint_nests_children_under_parents(): void
    {
        $parent = Category::factory()->create(['name' => 'Parent']);
        Category::factory()->create(['name' => 'Child', 'parent_id' => $parent->id]);

        $response = $this->getJson('/admin/categories-tree');

        $response->assertOk();
        $tree = collect($response->json('data'));
        $parentNode = $tree->firstWhere('name', 'Parent');
        $this->assertCount(1, $parentNode['children']);
        $this->assertSame('Child', $parentNode['children'][0]['name']);
    }

    public function test_delete_removes_the_cloudinary_asset(): void
    {
        $category = Category::factory()->create(['image_public_id' => 'categories/to-delete']);

        $this->deleteJson("/admin/categories/{$category->id}")->assertNoContent();

        $this->assertContains('categories/to-delete', $this->fakeCloudinary->destroyed);
    }
}
