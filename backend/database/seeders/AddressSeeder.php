<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        // The known test customer gets two addresses, one marked default —
        // exercises the "setting a new default unsets the old one" behavior
        // even in seeded data.
        $customer = User::where('email', 'customer@example.com')->first();

        if ($customer) {
            Address::factory()->create([
                'user_id' => $customer->id,
                'label' => 'home',
                'full_name' => 'Jane Customer',
                'is_default' => true,
            ]);

            Address::factory()->create([
                'user_id' => $customer->id,
                'label' => 'work',
                'full_name' => 'Jane Customer',
                'is_default' => false,
            ]);
        }

        // Every other customer (role=customer) gets one address, so admin
        // order-creation-adjacent flows and address list endpoints have
        // real data across many users, not just the one known account.
        User::where('role', 'customer')
            ->where('email', '!=', 'customer@example.com')
            ->get()
            ->each(fn (User $user) => Address::factory()->create(['user_id' => $user->id]));
    }
}
