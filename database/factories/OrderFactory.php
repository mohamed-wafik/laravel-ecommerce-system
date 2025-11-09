<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        return [
            'user_id' => \App\Models\User::factory(),
            'total_amount' => $this->faker->randomFloat(2, 20, 500),
            'status' => $this->faker->randomElement($statuses),
            'order_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'country' => $this->faker->country(),
            'payment_status' => $this->faker->randomElement($paymentStatuses),
            'payment_tsession_id' => null,
            'payment_id' => (string) Str::uuid(),
        ];
    }
}