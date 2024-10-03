<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'seat_id' => \App\Models\Seat::inRandomOrder()->first()->id,
            'customer_id' => \App\Models\Customer::inRandomOrder()->first()->id,
            // 'showtime_id' => \App\Models\Showtime::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->randomElement(['ordered', 'unplaced', 'settled']),
            'movie_id' => \App\Models\Movie::inRandomOrder()->first()->id,
        ];
    }
}
