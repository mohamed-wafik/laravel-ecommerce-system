<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Single source of truth for app-wide configurable values (delivery fees,
 * tax rate, currency, free-delivery threshold...). Backed by the
 * `settings` table so an admin can change them without a deploy;
 * cached so reading one on every checkout/product request isn't a query
 * every time.
 *
 * Defaults live here (DEFAULTS) so a fresh install works before an admin
 * has touched a single setting, and so every reader shares one definition
 * of "what even is the default delivery fee" instead of redefining it.
 */
class SettingService
{
    protected const CACHE_KEY = 'app_settings';

    protected const CACHE_TTL_SECONDS = 3600;

    protected const DEFAULTS = [
        'tax_rate' => 0.0,                  // e.g. 0.05 for 5%
        'delivery_fee_standard' => 5.00,
        'delivery_fee_express' => 15.00,
        'free_delivery_threshold' => 100.00, // subtotal at/above which delivery is free, 0 disables
        'currency' => 'USD',
        'store_name' => 'My Store',
    ];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default ?? self::DEFAULTS[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, mixed> defaults overlaid with whatever's stored
     */
    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            $stored = Setting::query()->pluck('value', 'key')->all();

            return array_merge(self::DEFAULTS, $stored);
        });
    }
}
