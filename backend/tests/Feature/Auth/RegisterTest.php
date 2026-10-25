<?php

namespace Tests\Feature\Auth;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_unverified_user_and_sends_otp(): void
    {
        $response = $this->postJson('/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertCreated()->assertJsonPath('success', true);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com', 'email_verified_at' => null]);
        $this->assertDatabaseHas('otps', ['identifier' => 'jane@example.com', 'type' => 'register']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'jane@example.com']);

        $response = $this->postJson('/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertUnprocessable()->assertJsonPath('success', false);
    }

    public function test_verify_register_with_correct_code_issues_tokens(): void
    {
        $user = User::factory()->unverified()->create(['email' => 'jane@example.com']);
        $otp = Otp::create([
            'user_id' => $user->id,
            'identifier' => 'jane@example.com',
            'code_hash' => Hash::make('123456'),
            'type' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/auth/register/verify', [
            'email' => 'jane@example.com',
            'code' => '123456',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['access_token', 'refresh_token', 'user']]);

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertTrue($otp->fresh()->is_used);
    }

    public function test_verify_register_with_wrong_code_fails(): void
    {
        $user = User::factory()->unverified()->create(['email' => 'jane@example.com']);
        Otp::create([
            'user_id' => $user->id,
            'identifier' => 'jane@example.com',
            'code_hash' => Hash::make('123456'),
            'type' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/auth/register/verify', [
            'email' => 'jane@example.com',
            'code' => '000000',
        ]);

        $response->assertUnprocessable()->assertJsonPath('success', false);
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
