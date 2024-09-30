<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showtime>
 */
class ShowtimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_time' => $this->faker->dateTime,
            'end_time' => $this->faker->dateTime,
            'auditorium_id' => \App\Models\Auditorium::factory(),
            'movie_id' => \App\Models\Movie::factory(),
        ];
    }
}
