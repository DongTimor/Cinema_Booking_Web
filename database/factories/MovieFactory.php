<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'trailer' => $this->faker->url,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'duration' => $this->faker->numberBetween(60, 180),
            'status' => $this->faker->randomElement(['active', 'inactive', 'impending']),
        ];
    }
}
