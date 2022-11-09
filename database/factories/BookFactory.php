<?php

namespace Database\Factories;

use App\Models\Editorial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'editorial_id' => Editorial::inRandomOrder()->first()->id,
            'title'        => fake()->sentence(),
            'publish_at'   => fake()->date(),
            'price'        => fake()->randomFloat(2,50,500)
        ];
    }
}
