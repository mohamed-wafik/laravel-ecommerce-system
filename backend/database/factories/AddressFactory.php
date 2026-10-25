<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => 'home',
            'full_name' => fake()->name(),
            'phone' => fake()->numerify('01#########'),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'street' => fake()->streetAddress(),
            'postal_code' => fake()->postcode(),
            'is_default' => true,
        ];
    }
}
