<?php

namespace Tests\Feature\Auth;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_verified_account_issues_tokens_directly(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'Password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.verified', true)
            ->assertJsonStructure(['data' => ['access_token', 'refresh_token']]);
    }

    public function test_login_with_unverified_account_sends_otp_instead_of_tokens(): void
    {
        User::factory()->unverified()->create([
            'email' => 'jane@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'Password123',
        ]);

        $response->assertOk()->assertJsonPath('data.verified', false);
        $this->assertArrayNotHasKey('access_token', $response->json('data'));
        $this->assertDatabaseHas('otps', ['identifier' => 'jane@example.com', 'type' => 'login']);
    }

    public function test_login_with_wrong_password_fails(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertUnprocessable()->assertJsonPath('success', false);
    }

    public function test_login_with_deactivated_account_fails(): void
    {
        User::factory()->inactive()->create([
            'email' => 'jane@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'Password123',
        ]);

        $response->assertUnprocessable()->assertJsonPath('success', false);
    }

    public function test_verify_login_issues_tokens(): void
    {
        $user = User::factory()->unverified()->create(['email' => 'jane@example.com']);
        Otp::create([
            'user_id' => $user->id,
            'identifier' => 'jane@example.com',
            'code_hash' => Hash::make('123456'),
            'type' => 'login',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/auth/login/verify', [
            'email' => 'jane@example.com',
            'code' => '123456',
        ]);

        $response->assertOk()->assertJsonStructure(['data' => ['access_token', 'refresh_token']]);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
