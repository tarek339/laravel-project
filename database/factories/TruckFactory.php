<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Truck>
 */
class TruckFactory extends Factory
{
    private static $plateCounter = 1000;

    private static $idCounter = 1000;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'license_plate' => $this->faker->randomLetter().$this->faker->randomLetter().'-'.str_pad(self::$plateCounter++, 4, '0', STR_PAD_LEFT),
            'identification_number' => $this->faker->optional(0.8) ? 'TRK'.str_pad(self::$idCounter++, 4, '0', STR_PAD_LEFT) : null,
            'next_major_inspection' => $this->faker->dateTimeBetween('now', '+1 year'),
            'next_safety_inspection' => $this->faker->dateTimeBetween('now', '+1 year'),
            'next_tachograph_inspection' => $this->faker->dateTimeBetween('now', '+1 year'),
            'additional_information' => $this->faker->optional()->paragraph(),
        ];
    }
}
