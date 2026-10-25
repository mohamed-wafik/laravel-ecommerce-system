<?php

namespace Tests\Feature\Customer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\FakesExternalServices;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use FakesExternalServices, InteractsWithAuthGuards, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeExternalServices();
    }

    public function test_show_returns_the_authenticated_user(): void
    {
        $user = $this->actingAsCustomer();

        $this->getJson('/customer/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_update_profile_name_and_phone(): void
    {
        $this->actingAsCustomer();

        $this->patchJson('/customer/profile', ['name' => 'Updated Name'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Name');
    }

    public function test_change_password_requires_correct_current_password(): void
    {
        $user = User::factory()->create(['password_hash' => Hash::make('OldPassword1')]);
        $this->actingAsCustomer($user);

        $this->postJson('/customer/profile/change-password', [
            'current_password' => 'WrongPassword',
            'password' => 'NewPassword1',
            'password_confirmation' => 'NewPassword1',
        ])->assertUnprocessable();
    }

    public function test_change_password_revokes_other_sessions_but_keeps_current(): void
    {
        $user = User::factory()->create(['password_hash' => Hash::make('OldPassword1')]);
        $currentToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(30));
        $user->createToken('access_token', ['access-api'], now()->addMinutes(30)); // another device

        $response = $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken)
            ->postJson('/customer/profile/change-password', [
                'current_password' => 'OldPassword1',
                'password' => 'NewPassword1',
                'password_confirmation' => 'NewPassword1',
            ]);

        $response->assertOk();
        $this->assertSame(1, $user->fresh()->tokens()->count());
        $this->assertTrue(Hash::check('NewPassword1', $user->fresh()->password_hash));
    }

    public function test_deactivate_account(): void
    {
        $user = $this->actingAsCustomer();

        $this->deleteJson('/customer/profile')->assertOk();

        $this->assertFalse($user->fresh()->is_active);
        $this->assertSame(0, $user->fresh()->tokens()->count());
    }
}
