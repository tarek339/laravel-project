<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trailer>
 */
class TrailerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'license_plate' => $this->faker->unique()->bothify('??-####'),
            'identification_number' => $this->faker->unique()->bothify('TRL####'),
            'next_major_inspection' => $this->faker->dateTimeBetween('now', '+1 year'),
            'next_safety_inspection' => $this->faker->dateTimeBetween('now', '+1 year'),
            'additional_information' => $this->faker->optional()->paragraph(),
        ];
    }
}
