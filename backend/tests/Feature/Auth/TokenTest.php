<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_refresh_token_rotates_and_returns_new_pair(): void
    {
        $user = User::factory()->create();
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(30));

        $response = $this->withHeader('Authorization', 'Bearer '.$refreshToken->plainTextToken)
            ->postJson('/auth/refresh');

        $response->assertOk()->assertJsonStructure(['data' => ['access_token', 'refresh_token']]);

        // The refresh token just used is now dead (rotation) — reusing it must fail.
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $refreshToken->accessToken->id]);
    }

    public function test_refresh_rejects_a_plain_access_token(): void
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(30));

        $response = $this->withHeader('Authorization', 'Bearer '.$accessToken->plainTextToken)
            ->postJson('/auth/refresh');

        $response->assertForbidden();
    }

    public function test_logout_revokes_only_the_current_token(): void
    {
        $user = User::factory()->create();
        $tokenA = $user->createToken('access_token', ['access-api'], now()->addMinutes(30));
        $user->createToken('access_token', ['access-api'], now()->addMinutes(30));

        $this->withHeader('Authorization', 'Bearer '.$tokenA->plainTextToken)
            ->postJson('/auth/logout')
            ->assertOk();

        $this->assertSame(1, $user->fresh()->tokens()->count());
    }

    public function test_logout_all_revokes_every_token(): void
    {
        $user = User::factory()->create();
        $tokenA = $user->createToken('access_token', ['access-api'], now()->addMinutes(30));
        $user->createToken('access_token', ['access-api'], now()->addMinutes(30));

        $this->withHeader('Authorization', 'Bearer '.$tokenA->plainTextToken)
            ->postJson('/auth/logout-all')
            ->assertOk();

        $this->assertSame(0, $user->fresh()->tokens()->count());
    }
}
