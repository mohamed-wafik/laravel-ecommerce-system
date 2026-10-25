<?php

namespace Tests\Feature\Customer;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithAuthGuards;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use InteractsWithAuthGuards, RefreshDatabase;

    public function test_list_only_returns_the_authenticated_users_addresses(): void
    {
        $user = $this->actingAsCustomer();
        Address::factory()->create(['user_id' => $user->id]);
        Address::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->getJson('/customer/addresses');

        $this->assertCount(1, $response->json('data'));
    }

    public function test_create_address(): void
    {
        $this->actingAsCustomer();

        $response = $this->postJson('/customer/addresses', [
            'full_name' => 'Jane Doe',
            'phone' => '0123456789',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'street' => '123 Main St',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('addresses', ['city' => 'Cairo']);
    }

    public function test_setting_is_default_unsets_other_defaults(): void
    {
        $user = $this->actingAsCustomer();
        $first = Address::factory()->create(['user_id' => $user->id, 'is_default' => true]);

        $this->postJson('/customer/addresses', [
            'full_name' => 'Jane Doe',
            'phone' => '0123456789',
            'country' => 'Egypt',
            'city' => 'Giza',
            'street' => '456 Other St',
            'is_default' => true,
        ])->assertCreated();

        $this->assertFalse($first->fresh()->is_default);
    }

    public function test_cannot_update_another_users_address(): void
    {
        $this->actingAsCustomer();
        $otherAddress = Address::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->patchJson("/customer/addresses/{$otherAddress->id}", ['city' => 'Hacked'])
            ->assertNotFound();
    }

    public function test_delete_address(): void
    {
        $user = $this->actingAsCustomer();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->deleteJson("/customer/addresses/{$address->id}")->assertOk();
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }
}
