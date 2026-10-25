<?php

namespace Database\Seeders;

use App\Services\SettingService;
use Illuminate\Database\Seeder;

/**
 * Not strictly required — SettingService::DEFAULTS already covers every
 * key — but seeding explicit rows demonstrates the admin-editable path
 * and gives GET /admin/settings something other than pure defaults to show.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        app(SettingService::class)->setMany([
            'tax_rate' => 0.05,
            'delivery_fee_standard' => 5.00,
            'delivery_fee_express' => 15.00,
            'free_delivery_threshold' => 100.00,
            'currency' => 'USD',
            'store_name' => 'Demo Store',
        ]);
    }
}
