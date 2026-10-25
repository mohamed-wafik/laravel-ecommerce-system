<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 300);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax' => 0,
            'shipping_fee' => 0,
            'total' => $subtotal,
            'currency' => 'USD',
            'shipping_address_id' => Address::factory(),
            'idempotency_key' => (string) Str::uuid(),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => ['status' => 'paid']);
    }
}
