<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Every credential here is deliberately fixed (not random) and matches
 * the defaults baked into postman/ecommerce-api-local.postman_environment.json
 * — import the collection, hit "Admin Login" / use these directly, and
 * everything just works without hunting for a real id first.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // The one admin — matches Postman's {{admin_email}}/{{admin_password}}.
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        // A second admin, deactivated — for testing that the admin guard
        // actually rejects a deactivated admin's token, not just "some auth".
        User::factory()->admin()->inactive()->create([
            'name' => 'Deactivated Admin',
            'email' => 'deactivated-admin@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        // The "just log in and go" customer — verified, matches Postman's
        // {{test_email}} default, has a known password so login doesn't
        // need the OTP dance during manual testing.
        User::factory()->create([
            'name' => 'Jane Customer',
            'email' => 'customer@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        // An unverified customer — for testing the login->OTP->verify branch.
        User::factory()->unverified()->create([
            'name' => 'Unverified Customer',
            'email' => 'unverified@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        // A deactivated customer — login should be rejected outright.
        User::factory()->inactive()->create([
            'name' => 'Deactivated Customer',
            'email' => 'deactivated-customer@example.com',
            'password_hash' => Hash::make('Password123'),
        ]);

        // A Google-linked account — no usable password, google_id set.
        User::factory()->withGoogle()->create([
            'name' => 'Google Customer',
            'email' => 'google-user@example.com',
        ]);

        // A pile of ordinary random customers for list/pagination/search testing.
        User::factory()->count(15)->create();
    }
}
