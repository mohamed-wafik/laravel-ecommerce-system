<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'provider' => 'stripe',
            'checkout_session_id' => 'cs_test_'.Str::random(20),
            'idempotency_key' => (string) Str::uuid(),
            'amount' => fake()->randomFloat(2, 20, 300),
            'currency' => 'USD',
            'status' => 'pending',
        ];
    }

    public function succeeded(): static
    {
        return $this->state(fn () => ['status' => 'succeeded', 'paid_at' => now()]);
    }
}