<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Services\AiSearchService;
use Illuminate\Support\Facades\Http;

class ProductTestSearch extends TestCase
{
    use RefreshDatabase;

    public function test_ai_product_search_with_mock()
    {
        // Create a category first
        $category = Category::factory()->create();

        // Create sample products
        $redShirt = Product::factory()->create([
            'title' => 'Red Shirt',
            'description' => 'A bright red shirt',
            'category_id' => $category->id
        ]);

        // Mock OpenAI API response
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'keywords' => ['red', 'shirt', 'clothing'],
                                'categories' => ['apparel'],
                                'description' => 'User is looking for red clothing items'
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $aiSearchService = app(AiSearchService::class);
        $results = $aiSearchService->searchProducts('red clothing');

        // Assert
        $this->assertIsArray($results);
        $this->assertArrayHasKey('product_ids', $results);
        $this->assertContains('red', $results['keywords']);
        
        echo "\n✓ Test passed! Keywords: " . implode(', ', $results['keywords']) . "\n";
    }
}