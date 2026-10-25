<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    public function test_admin_can_list_users(): void
    {
        $this->actingAsAdmin();
        User::factory()->count(3)->create();

        $this->getJson('/admin/users')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(4, 'data.items'); // 3 + the acting admin
    }

    public function test_admin_can_create_a_user(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/admin/users', [
            'name' => 'New Customer',
            'email' => 'new@example.com',
            'password' => 'Password123',
            'role' => 'customer',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_admin_can_update_a_user(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create(['name' => 'Old Name']);

        $this->patchJson("/admin/users/{$user->id}", ['name' => 'New Name'])
            ->assertOk()
            ->assertJsonPath('data.name', 'New Name');
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create(['is_active' => true]);

        $this->postJson("/admin/users/{$user->id}/toggle-active")
            ->assertOk()
            ->assertJsonPath('data.is_active', false);
    }

    public function test_admin_can_delete_a_user(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        $this->deleteJson("/admin/users/{$user->id}")->assertNoContent();
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_search_filter_matches_name_or_email(): void
    {
        $this->actingAsAdmin();
        User::factory()->create(['name' => 'Findme Person', 'email' => 'unrelated@example.com']);
        User::factory()->create(['name' => 'Other Person', 'email' => 'other@example.com']);

        $response = $this->getJson('/admin/users?search=Findme');

        $names = collect($response->json('data.items'))->pluck('name');
        $this->assertTrue($names->contains('Findme Person'));
        $this->assertFalse($names->contains('Other Person'));
    }
}
