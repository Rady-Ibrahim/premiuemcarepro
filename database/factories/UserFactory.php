<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'test customer',
            'email' => "customer@customer.com",
            'type' => "customer",
            'uuid' => "sdfrhtistss",
            'address' => "test address",
            'phone' => "01201478220",
            'nid' => "123456789",
            'active' => true,
            'password' => '$2y$12$Vs/TH.i74LnJaepIAE7JjeYBT6zOHjZfJqVPXDvC3rH.ghwOdpZOW',
        ];
    }
}
