<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Configuration
    |--------------------------------------------------------------------------
    */

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'shipping_rates' => [
        'standard' => 30.00,      // 30 EGP
        'express' => 60.00,       // 60 EGP
        'pickup' => 0.00,         // Free pickup
    ],

    'tax_rate' => 0.14,           // 14% tax

    'currency' => 'egp',          // Egyptian Pound
];
