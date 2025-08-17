<?php

use App\Models\Driver;

test('app:check-drivers-command command displays drivers with expiring licenses', function () {
    // Create test drivers with expiring licenses
    $driver1 = Driver::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'license_number' => 'ABC123',
        'license_expiry_date' => now()->addDays(30)->toDateString(), // Expires in 30 days
    ]);

    $driver2 = Driver::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'license_number' => 'XYZ789',
        'license_expiry_date' => now()->addDays(30)->toDateString(), // Expires in 30 days
    ]);

    // Create a driver with license not expiring soon (should not appear)
    Driver::factory()->create([
        'first_name' => 'Bob',
        'last_name' => 'Johnson',
        'email' => 'bob.johnson@example.com',
        'license_number' => 'LMN456',
        'license_expiry_date' => now()->addDays(31)->toDateString(), // Expires in 31 days
    ]);

    $this->artisan('app:check-drivers-command')
        ->expectsOutput("Driver {$driver1->first_name} {$driver1->last_name} license expires in one month")
        ->expectsOutput("Driver {$driver2->first_name} {$driver2->last_name} license expires in one month")
        ->assertExitCode(0);

    // Test the data separately by querying the database directly
    $expiringDrivers = Driver::whereDate('license_expiry_date', '=', now()->addDays(30)->toDateString())->get();

    expect($expiringDrivers)->toHaveCount(2);
    expect($expiringDrivers->first()->first_name)->toBe('John');
    expect($expiringDrivers->last()->first_name)->toBe('Jane');

    // Convert to array if needed
    $expiringDriversArray = $expiringDrivers->toArray();

    expect($expiringDriversArray)->toHaveCount(2);
    expect($expiringDriversArray[0]['first_name'])->toBe('John');
    expect($expiringDriversArray[1]['first_name'])->toBe('Jane');
});
