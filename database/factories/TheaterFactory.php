<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theater>
 */
class TheaterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'phone' => $this->faker->numerify('###########'), // Giới hạn 12 ký tự
            'email' => $this->faker->unique()->safeEmail,
            'description' => $this->faker->paragraph,
        ];
    }
}
