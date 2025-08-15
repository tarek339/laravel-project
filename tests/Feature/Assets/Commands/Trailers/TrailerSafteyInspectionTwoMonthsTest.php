<?php

use App\Models\Trailer;

test('check:trailers command displays trailers next safety inspection dates', function () {

    $trailer1 = Trailer::factory()->create([
        'license_plate' => 'ABC123',
        'next_safety_inspection' => now()->addDays(60),
    ]);

    $trailer2 = Trailer::factory()->create([
        'license_plate' => 'XYZ789',
        'next_safety_inspection' => now()->addDays(60),
    ]);

    Trailer::factory()->create([
        'license_plate' => 'DEF456',
        'next_safety_inspection' => now()->addDays(31),
    ]);

    $this->artisan('app:check-trailers-command')
        ->expectsOutput("Trailer {$trailer1->license_plate} next safety inspection is in two months - {$trailer1->next_safety_inspection}")
        ->expectsOutput("Trailer {$trailer2->license_plate} next safety inspection is in two months - {$trailer2->next_safety_inspection}")
        ->assertExitCode(0);

    // Test the data separately by querying the database directly
    $nextSafetyInspectionOneMonth = Trailer::whereDate('next_safety_inspection', '=', now()->addDays(60)->toDateString())->get();

    // Convert to array if needed
    $nextSafetyInspectionOneMonthArray = $nextSafetyInspectionOneMonth->toArray();

    expect($nextSafetyInspectionOneMonthArray)->toHaveCount(2);
    expect($nextSafetyInspectionOneMonthArray[0]['license_plate'])->toBe('ABC123');
    expect($nextSafetyInspectionOneMonthArray[1]['license_plate'])->toBe('XYZ789');
});
