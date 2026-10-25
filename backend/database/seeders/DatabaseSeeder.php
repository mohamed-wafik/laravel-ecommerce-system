<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Order matters — later seeders depend on records earlier ones create
 * (products need categories/brands, orders need products/addresses, etc.).
 *
 * Run with: php artisan db:seed
 * Or fresh:  php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            AddressSeeder::class,
            ReviewSeeder::class,
            OrderSeeder::class,
            CartSeeder::class,
        ]);

        $this->command?->info('Seeded. Known logins (all password: Password123):');
        $this->command?->table(['Role', 'Email', 'Notes'], [
            ['admin', 'admin@example.com', 'Use with POST /admin/login'],
            ['admin', 'deactivated-admin@example.com', 'is_active=false — should be rejected'],
            ['customer', 'customer@example.com', 'Verified, has address/cart/orders in every status'],
            ['customer', 'unverified@example.com', 'email_verified_at=null — triggers the OTP branch on login'],
            ['customer', 'deactivated-customer@example.com', 'is_active=false — should be rejected'],
            ['customer', 'google-user@example.com', 'google_id set, no usable password'],
        ]);
        $this->command?->info('Guest cart token for GET /guest/cart: seed-guest-token-00000000');
        $this->command?->info('Known product slug for GET /guest/products/{slug}: sample-product');
    }
}
