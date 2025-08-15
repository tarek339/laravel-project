<?php

use App\Models\Driver;

test('check:drivers command displays drivers with expiring licenses', function () {
    // Create test drivers with expiring licenses
    $driver1 = Driver::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'driver_qualification_number' => 'ABC123',
        'driver_qualification_expiry_date' => now()->addDays(30)->toDateString(), // Expires in 30 days
    ]);

    $driver2 = Driver::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'driver_qualification_number' => 'XYZ789',
        'driver_qualification_expiry_date' => now()->addDays(30)->toDateString(), // Expires in 30 days
    ]);

    // Create a driver with license not expiring soon (should not appear)
    Driver::factory()->create([
        'first_name' => 'Bob',
        'last_name' => 'Johnson',
        'email' => 'bob.johnson@example.com',
        'driver_qualification_number' => 'LMN456',
        'driver_qualification_expiry_date' => now()->addDays(31)->toDateString(), // Expires in 31 days
    ]);

    $this->artisan('check:drivers')
        ->expectsOutput("Driver {$driver1->first_name} {$driver1->last_name} driver qualification expires in one month")
        ->expectsOutput("Driver {$driver2->first_name} {$driver2->last_name} driver qualification expires in one month")
        ->assertExitCode(0);

    // Test the data separately by querying the database directly
    $expiringDrivers = Driver::whereDate('driver_qualification_expiry_date', '=', now()->addDays(30)->toDateString())->get();

    expect($expiringDrivers)->toHaveCount(2);
    expect($expiringDrivers->first()->first_name)->toBe('John');
    expect($expiringDrivers->last()->first_name)->toBe('Jane');

    // Convert to array if needed
    $expiringDriversArray = $expiringDrivers->toArray();

    expect($expiringDriversArray)->toHaveCount(2);
    expect($expiringDriversArray[0]['first_name'])->toBe('John');
    expect($expiringDriversArray[1]['first_name'])->toBe('Jane');
});

test('check:drivers command handles no expiring licenses', function () {
    // Create drivers with licenses that don't expire soon
    Driver::factory()->create([
        'first_name' => 'Alice',
        'last_name' => 'Williams',
        'email' => 'alice.williams@example.com',
        'driver_qualification_number' => 'DEF123',
        'driver_qualification_expiry_date' => now()->addDays(31)->toDateString(),
    ]);

    Driver::factory()->create([
        'first_name' => 'Bob',
        'last_name' => 'Johnson',
        'email' => 'bob.johnson@example.com',
        'driver_qualification_number' => 'GHI789',
        'driver_qualification_expiry_date' => now()->addDays(35)->toDateString(),
    ]);

    Driver::factory()->create([
        'first_name' => 'Charlie',
        'last_name' => 'Brown',
        'email' => 'charlie.brown@example.com',
        'driver_qualification_number' => 'JKL012',
        'driver_qualification_expiry_date' => now()->addDays(61)->toDateString(),
    ]);

    $this->artisan('check:drivers')
        ->assertExitCode(0);

    // Test that no drivers have expiring licenses
    $expiringDrivers = Driver::whereDate('driver_qualification_expiry_date', '=', now()->addDays(30)->toDateString())->get();

    // Convert to array if needed
    $expiringDriversArray = $expiringDrivers->toArray();

    expect($expiringDriversArray)->toBeEmpty();
});
