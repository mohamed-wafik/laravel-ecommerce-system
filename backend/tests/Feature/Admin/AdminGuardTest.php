<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_request_is_rejected(): void
    {
        $this->getJson('/admin/dashboard/summary')->assertUnauthorized();
    }

    public function test_customer_token_cannot_access_admin_routes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $token = $customer->createToken('access_token', ['access-api'], now()->addMinutes(30));

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->getJson('/admin/dashboard/summary')
            ->assertForbidden();
    }

    public function test_deactivated_admin_is_rejected(): void
    {
        $admin = User::factory()->admin()->inactive()->create();
        $token = $admin->createToken('access_token', ['admin'], now()->addMinutes(30));

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->getJson('/admin/dashboard/summary')
            ->assertForbidden();
    }

    public function test_active_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->admin()->create();
        $token = $admin->createToken('access_token', ['admin'], now()->addMinutes(30));

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->getJson('/admin/dashboard/summary')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_admin_login_rejects_non_admin_accounts(): void
    {
        $customer = User::factory()->create([
            'email' => 'customer@example.com',
            'password_hash' => bcrypt('Password123'),
        ]);

        $this->postJson('/admin/login', [
            'email' => 'customer@example.com',
            'password' => 'Password123',
        ])->assertForbidden();
    }

    public function test_admin_login_issues_a_scoped_token(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password_hash' => bcrypt('Password123'),
        ]);

        $response = $this->postJson('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'Password123',
        ]);

        $response->assertOk()->assertJsonStructure(['data' => ['token', 'user']]);
    }
}
