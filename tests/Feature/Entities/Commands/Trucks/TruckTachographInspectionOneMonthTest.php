<?php

use App\Models\Truck;

test('check:trucks command displays trucks next tachograph inspection dates', function () {

    $truck1 = Truck::factory()->create([
        'license_plate' => 'ABC123',
        'next_tachograph_inspection' => now()->addDays(30),
    ]);

    $truck2 = Truck::factory()->create([
        'license_plate' => 'XYZ789',
        'next_tachograph_inspection' => now()->addDays(30),
    ]);

    Truck::factory()->create([
        'license_plate' => 'DEF456',
        'next_tachograph_inspection' => now()->addDays(31),
    ]);

    $this->artisan('app:check-trucks-command')
        ->expectsOutput("Truck {$truck1->license_plate} next tachograph inspection is next month - {$truck1->next_tachograph_inspection}")
        ->expectsOutput("Truck {$truck2->license_plate} next tachograph inspection is next month - {$truck2->next_tachograph_inspection}")
        ->assertExitCode(0);

    // Test the data separately by querying the database directly
    $nextTachographInspectionOneMonth = Truck::whereDate('next_tachograph_inspection', '=', now()->addDays(30)->toDateString())->get();

    // Convert to array if needed
    $nextTachographInspectionOneMonthArray = $nextTachographInspectionOneMonth->toArray();

    expect($nextTachographInspectionOneMonthArray)->toHaveCount(2);
    expect($nextTachographInspectionOneMonthArray[0]['license_plate'])->toBe('ABC123');
    expect($nextTachographInspectionOneMonthArray[1]['license_plate'])->toBe('XYZ789');
});
