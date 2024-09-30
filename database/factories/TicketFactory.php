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
            'user_id' => \App\Models\User::factory(),
            'seat_id' => \App\Models\Seat::factory(),
            'customer_id' => \App\Models\Customer::factory(),
            'showtime_id' => \App\Models\Showtime::factory(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->randomElement(['ordered', 'unplaced', 'settled']),
        ];
    }
}
