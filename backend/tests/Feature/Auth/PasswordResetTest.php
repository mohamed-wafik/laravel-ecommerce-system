<?php

namespace Tests\Feature\Auth;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_responds_the_same_whether_or_not_email_exists(): void
    {
        $existing = $this->postJson('/auth/forgot-password', ['email' => 'nobody@example.com']);
        $existing->assertOk()->assertJsonPath('success', true);

        User::factory()->create(['email' => 'jane@example.com']);
        $real = $this->postJson('/auth/forgot-password', ['email' => 'jane@example.com']);

        $this->assertSame($existing->json('message'), $real->json('message'));
        $this->assertDatabaseHas('otps', ['identifier' => 'jane@example.com', 'type' => 'reset_password']);
        $this->assertDatabaseMissing('otps', ['identifier' => 'nobody@example.com']);
    }

    public function test_reset_password_with_valid_code_changes_password_and_revokes_sessions(): void
    {
        $user = User::factory()->create(['email' => 'jane@example.com']);
        $user->createToken('old-session');

        Otp::create([
            'user_id' => $user->id,
            'identifier' => 'jane@example.com',
            'code_hash' => Hash::make('654321'),
            'type' => 'reset_password',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->postJson('/auth/reset-password', [
            'email' => 'jane@example.com',
            'code' => '654321',
            'password' => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertTrue(Hash::check('NewPassword456', $user->fresh()->password_hash));
        $this->assertSame(0, $user->fresh()->tokens()->count());
    }

    public function test_reset_password_with_expired_code_fails(): void
    {
        $user = User::factory()->create(['email' => 'jane@example.com']);
        Otp::create([
            'user_id' => $user->id,
            'identifier' => 'jane@example.com',
            'code_hash' => Hash::make('654321'),
            'type' => 'reset_password',
            'expires_at' => now()->subMinute(),
        ]);

        $response = $this->postJson('/auth/reset-password', [
            'email' => 'jane@example.com',
            'code' => '654321',
            'password' => 'NewPassword456',
            'password_confirmation' => 'NewPassword456',
        ]);

        $response->assertUnprocessable();
    }
}
