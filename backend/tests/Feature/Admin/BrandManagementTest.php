<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Concerns\FakesExternalServices;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class BrandManagementTest extends TestCase
{
    use FakesExternalServices, InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeExternalServices();
        $this->actingAsAdmin();
    }

    public function test_create_brand_with_logo(): void
    {
        $response = $this->postJson('/admin/brands', [
            'name' => 'Nike',
            'logo' => UploadedFile::fake()->image('logo.png'),
        ]);

        $response->assertCreated();
        $this->assertNotEmpty($this->fakeCloudinary->uploaded);
        $this->assertDatabaseHas('brands', ['name' => 'Nike']);
    }

    public function test_toggle_active(): void
    {
        $brand = Brand::factory()->create(['is_active' => true]);

        $this->postJson("/admin/brands/{$brand->id}/toggle-active")
            ->assertOk()
            ->assertJsonPath('data.is_active', false);
    }

    public function test_delete_removes_logo_from_cloudinary(): void
    {
        $brand = Brand::factory()->create(['logo_public_id' => 'brands/to-delete']);

        $this->deleteJson("/admin/brands/{$brand->id}")->assertNoContent();

        $this->assertContains('brands/to-delete', $this->fakeCloudinary->destroyed);
    }

    public function test_search_filters_by_name(): void
    {
        Brand::factory()->create(['name' => 'Adidas']);
        Brand::factory()->create(['name' => 'Puma']);

        $response = $this->getJson('/admin/brands?search=Adidas');

        $names = collect($response->json('data.items'))->pluck('name');
        $this->assertTrue($names->contains('Adidas'));
        $this->assertFalse($names->contains('Puma'));
    }
}
