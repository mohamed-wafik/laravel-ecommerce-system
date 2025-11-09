<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Product;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemOrder> */
class ItemOrderFactory extends Factory
{
    protected $model = ItemOrder::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $unit_price = $this->faker->randomFloat(2, 5, 500);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'subtotal' => round($quantity * $unit_price, 2),
        ];
    }
}
