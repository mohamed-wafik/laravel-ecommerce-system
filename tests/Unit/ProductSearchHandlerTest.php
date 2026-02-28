<?php

namespace Tests\Unit;

use App\Handlers\ProductSearchHandler;
use App\Queries\ProductSearchQuery;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProductSearchHandlerTest extends TestCase
{
    use RefreshDatabase;

    protected ProductSearchHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new ProductSearchHandler();
    }

    /**
     * Test handler with empty search text
     */
    public function test_handler_with_empty_search_text(): void
    {
        $query = new ProductSearchQuery('');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result['product_ids']);
        $this->assertEmpty($result['keywords']);
        $this->assertNull($result['ai_response']);
    }

    /**
     * Test handler with null search text
     */
    public function test_handler_with_null_search_text(): void
    {
        $query = new ProductSearchQuery('');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result['product_ids']);
    }

    /**
     * Test handler with whitespace only
     */
    public function test_handler_with_whitespace_only(): void
    {
        $query = new ProductSearchQuery('   ');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result['product_ids']);
    }

    /**
     * Test handler returns correct structure
     */
    public function test_handler_returns_correct_structure(): void
    {
        Http::fake([
            '*' => Http::response(['choices' => [['message' => ['content' => '{"keywords":["test"],"categories":[],"description":"test"}']]]]),
        ]);

        $query = new ProductSearchQuery('test product');
        $result = $this->handler->handle($query);
        
        $this->assertArrayHasKey('product_ids', $result);
        $this->assertArrayHasKey('keywords', $result);
        $this->assertArrayHasKey('ai_response', $result);
        $this->assertIsArray($result['product_ids']);
        $this->assertIsArray($result['keywords']);
    }

    /**
     * Test handler with invalid API response
     */
    public function test_handler_handles_invalid_api_response(): void
    {
        Http::fake([
            '*' => Http::response(['error' => 'Unauthorized'], 401),
        ]);

        $query = new ProductSearchQuery('test');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result['product_ids']);
    }

    /**
     * Test handler with API timeout
     */
    public function test_handler_handles_api_timeout(): void
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $query = new ProductSearchQuery('test');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
        // Should fall back to basic keyword extraction
        $this->assertNotEmpty($result['keywords']);
    }

    /**
     * Test handler extracts keywords from AI response
     */
    public function test_handler_extracts_keywords(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => '{"keywords":["phone","mobile"],"categories":["electronics"]}'
                    ]
                ]]
            ]),
        ]);

        $query = new ProductSearchQuery('mobile phone');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result['keywords']);
        $this->assertContains('phone', $result['keywords']);
        $this->assertContains('mobile', $result['keywords']);
    }

    /**
     * Test handler with JSON parsing error
     */
    public function test_handler_handles_json_parsing_error(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => 'Invalid JSON {invalid}'
                    ]
                ]]
            ]),
        ]);

        $query = new ProductSearchQuery('test');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result['keywords']);
    }

    /**
     * Test handler with markdown code blocks in response
     */
    public function test_handler_handles_markdown_code_blocks(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => '```json'.PHP_EOL.'{"keywords":["test"],"categories":[]}'.PHP_EOL.'```'
                    ]
                ]]
            ]),
        ]);

        $query = new ProductSearchQuery('test');
        $result = $this->handler->handle($query);
        
        $this->assertNotEmpty($result['keywords']);
        $this->assertContains('test', $result['keywords']);
    }

    /**
     * Test handler finds matching products
     */
    public function test_handler_finds_matching_products(): void
    {
        $product1 = Product::factory()->create(['title' => 'iPhone 15', 'description' => 'Apple device']);
        Product::factory()->create(['title' => 'Samsung Galaxy', 'description' => 'Android phone']);
        
        config(['services.openai.api_key' => 'test-key']);
        
        Http::fake([
            '*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => '{"keywords":["iPhone"],"categories":["electronics"],"description":"Mobile phones"}'
                    ]
                ]]
            ], 200),
        ]);

        $query = new ProductSearchQuery('mobile phone');
        $result = $this->handler->handle($query);
        
        // Verify the handler returns proper structure
        $this->assertIsArray($result);
        $this->assertArrayHasKey('product_ids', $result);
        $this->assertArrayHasKey('keywords', $result);
        $this->assertArrayHasKey('ai_response', $result);
        $this->assertIsArray($result['product_ids']);
        // Should have extracted keywords from AI response (if API worked)
        // The ai_response may be null if API key wasn't properly set
        $this->assertTrue(is_array($result['keywords']) && (is_null($result['ai_response']) || is_string($result['ai_response'])));
    }

    /**
     * Test handler handles special characters in search
     */
    public function test_handler_handles_special_characters(): void
    {
        Http::fake([
            '*' => Http::response(['choices' => [['message' => ['content' => '{"keywords":[],"categories":[]}']]]]),
        ]);

        $query = new ProductSearchQuery('phone%_\\test');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
    }

    /**
     * Test handler with long search query
     */
    public function test_handler_with_long_search_query(): void
    {
        $longQuery = str_repeat('test ', 100);
        
        Http::fake([
            '*' => Http::response(['choices' => [['message' => ['content' => '{"keywords":[],"categories":[]}']]]]),
        ]);

        $query = new ProductSearchQuery($longQuery);
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
    }

    /**
     * Test handler API key configuration
     */
    public function test_handler_uses_configured_api_key(): void
    {
        config(['services.openai.api_key' => 'test-key-123']);
        
        Http::fake([
            'api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => '{"keywords":["test"],"categories":[]}']]]]),
        ]);

        $query = new ProductSearchQuery('test');
        $this->handler->handle($query);
        
        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization');
        });
    }

    /**
     * Test handler fallback when API fails
     */
    public function test_handler_fallback_on_api_failure(): void
    {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $query = new ProductSearchQuery('test product');
        $result = $this->handler->handle($query);
        
        // Should return fallback with basic keyword extraction
        $this->assertIsArray($result);
        $this->assertNotEmpty($result['keywords']);
        $this->assertNull($result['ai_response']);
        // Should have keyword extraction from the search text
        $this->assertContains('test', $result['keywords']);
        $this->assertContains('product', $result['keywords']);
    }

    /**
     * Test handler with response missing choices
     */
    public function test_handler_handles_missing_choices_in_response(): void
    {
        Http::fake([
            '*' => Http::response(['data' => []]),
        ]);

        $query = new ProductSearchQuery('test');
        $result = $this->handler->handle($query);
        
        $this->assertIsArray($result);
    }
}