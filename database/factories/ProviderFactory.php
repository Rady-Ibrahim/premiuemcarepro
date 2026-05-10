<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = json_decode(File::get(database_path('factories/locations.json')), true);
        $city = $this->faker->randomElement(array_keys($locations));
        $area = $this->faker->randomElement($locations[$city]);

        return [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement(['hospital', 'clinic', 'pharmacy']),
            'specializations' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'email' => $this->faker->safeEmail,
            'latitude' => $this->faker->latitude,
            'city' => $city,
            'area' => $area,
            'longitude' => $this->faker->longitude,
            'is_insurance_accepted' => $this->faker->boolean,
            'image' => $this->faker->imageUrl(640, 480, 'hospital', true, 'Faker'),
        ];
    }
}
