<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
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
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'license_number' => $this->faker->unique()->bothify('LIC-####'),
            'license_expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+5 years'),
            'driver_card_number' => $this->faker->optional()->bothify('CARD-####'),
            'driver_card_expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+5 years'),
            'driver_qualification_number' => $this->faker->optional()->bothify('QUAL-####'),
            'driver_qualification_expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+5 years'),
            'street' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'additional_information' => $this->faker->optional()->paragraph(),
        ];
    }
}
