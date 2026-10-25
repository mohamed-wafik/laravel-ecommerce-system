<?php

namespace Tests\Concerns;

use App\Models\User;
use Database\Factories\UserFactory;
use Laravel\Sanctum\Sanctum;

/**
 * Wraps Sanctum::actingAs() with the app's two guards so tests read as
 * intent ("as an admin", "as a customer") instead of repeating guard
 * names and ability arrays everywhere.
 */
trait InteractsWithAuthGuards
{
    protected function actingAsAdmin(?User $admin = null): User
    {
        $admin ??= User::factory()->admin()->create();

        Sanctum::actingAs($admin, ['admin'], 'admin');

        return $admin;
    }

    protected function actingAsCustomer(?User $customer = null): User
    {
        $customer ??= User::factory()->create();

        Sanctum::actingAs($customer, ['access-api'], 'sanctum');

        return $customer;
    }
}
