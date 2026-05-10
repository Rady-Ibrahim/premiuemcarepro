<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => $this->faker->randomElement(Ticket::all()->pluck('id')),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')),
            'message' => $this->faker->paragraph(4),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
