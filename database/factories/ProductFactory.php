<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'description' => fake()->paragraph(),
            'category' => fake()->word(),
            'image' => fake()->imageUrl(),
        ];
    }
}
