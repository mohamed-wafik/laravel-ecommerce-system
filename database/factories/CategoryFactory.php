<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['electronics', 'fashion', 'books', 'furniture', 'toys', 'beauty', 'sports'];
        $name = $this->faker->unique()->randomElement($categories);

        return [
            'title' => ucfirst($name),
            'description' => $this->faker->sentence(8),
            'image' => $this->faker->imageUrl(640, 480, $name, true),
        ];
    }
}