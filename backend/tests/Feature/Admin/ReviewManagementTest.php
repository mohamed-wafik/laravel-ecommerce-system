<?php

namespace Tests\Feature\Admin;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class ReviewManagementTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_approve_review(): void
    {
        $review = Review::factory()->create(['is_approved' => false]);

        $this->postJson("/admin/reviews/{$review->id}/approve")
            ->assertOk()
            ->assertJsonPath('data.is_approved', true);
    }

    public function test_reject_review(): void
    {
        $review = Review::factory()->approved()->create();

        $this->postJson("/admin/reviews/{$review->id}/reject")
            ->assertOk()
            ->assertJsonPath('data.is_approved', false);
    }

    public function test_delete_review(): void
    {
        $review = Review::factory()->create();

        $this->deleteJson("/admin/reviews/{$review->id}")->assertNoContent();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
