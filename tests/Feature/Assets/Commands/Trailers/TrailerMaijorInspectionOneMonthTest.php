<?php

use App\Models\Trailer;

test('check:trailers command displays trailers next major inspection dates', function () {

    $trailer1 = Trailer::factory()->create([
        'license_plate' => 'ABC123',
        'next_major_inspection' => now()->addDays(30),
    ]);

    $trailer2 = Trailer::factory()->create([
        'license_plate' => 'XYZ789',
        'next_major_inspection' => now()->addDays(30),
    ]);

    Trailer::factory()->create([
        'license_plate' => 'DEF456',
        'next_major_inspection' => now()->addDays(31),
    ]);

    $this->artisan('app:check-trailers-command')
        ->expectsOutput("Trailer {$trailer1->license_plate} next major inspection is next month - {$trailer1->next_major_inspection}")
        ->expectsOutput("Trailer {$trailer2->license_plate} next major inspection is next month - {$trailer2->next_major_inspection}")
        ->assertExitCode(0);

    // Test the data separately by querying the database directly
    $nextMajorInspectionOneMonth = Trailer::whereDate('next_major_inspection', '=', now()->addDays(30)->toDateString())->get();

    // Convert to array if needed
    $nextMajorInspectionOneMonthArray = $nextMajorInspectionOneMonth->toArray();

    expect($nextMajorInspectionOneMonthArray)->toHaveCount(2);
    expect($nextMajorInspectionOneMonthArray[0]['license_plate'])->toBe('ABC123');
    expect($nextMajorInspectionOneMonthArray[1]['license_plate'])->toBe('XYZ789');
});
