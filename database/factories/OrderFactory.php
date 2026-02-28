<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $shippingMethods = ['standard', 'express', 'pickup'];
        $paymentMethods = ['cod', 'card', 'wallet'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        $subtotal = $this->faker->randomFloat(2, 20, 500);
        $shippingCost = config('payment.shipping_rates.' . $this->faker->randomElement($shippingMethods));
        $tax = round($subtotal * config('payment.tax_rate'), 2); // 14% tax
        $discount = $this->faker->randomFloat(2, 0, 50);
        $total = round($subtotal + $shippingCost + $tax - $discount, 2);

        return [
            'user_id' => \App\Models\User::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'customer_phone' => $this->faker->phoneNumber(),
            'shipping_address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'shipping_method' => $this->faker->randomElement($shippingMethods),
            'shipping_cost' => $shippingCost,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'payment_status' => $this->faker->randomElement($paymentStatuses),
            'order_status' => $this->faker->randomElement($orderStatuses),
        ];
    }
}