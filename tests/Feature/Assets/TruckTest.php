<?php

use App\Models\Company;
use App\Models\Truck;
use App\Models\User;

function createTruckWithDependencies(): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);
    $truck = Truck::factory()->create(['company_id' => $company->id]);

    return compact('user', 'company', 'truck');
}

function createMultipleTrucksWithDependencies(int $count = 3): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);
    $trucks = Truck::factory()->count($count)->create(['company_id' => $company->id]);

    return compact('user', 'company', 'trucks');
}

test('trucks can be fetched and displayed', function () {
    ['user' => $user, 'company' => $company, 'trucks' => $trucks] = createMultipleTrucksWithDependencies();

    $this->actingAs($user)
        ->get('/trucks')
        ->assertInertia(
            fn ($page) => $page
                ->component('trucks/trucks-table')
                ->has('trucks')
        )
        ->assertStatus(200);
});

test('a truck can be added', function () {
    // Arrange: Create an authenticated user
    $user = User::factory()->create();

    // Create a Company for the Driver
    $company = Company::factory()->create(['user_id' => $user->id]);

    // Prepare the Truck data
    $truckData = [
        'company_id' => $company->id,
        'license_plate' => 'AB-C-123',
        'identification_number' => 'ID123456',
        'next_major_inspection' => '2025-12-31',
        'next_safety_inspection' => '2025-12-31',
        'next_tachograph_inspection' => '2025-12-31',
        'additional_information' => 'No additional information',
    ];

    // Act: Send POST request to add a Truck
    $response = $this->actingAs($user)
        ->post(route('truck.store'), $truckData);

    // Assert: Check that the Truck was saved to the database
    $this->assertDatabaseHas('trucks', $truckData);

    // Check that the response is a redirect (successful creation)
    $response->assertStatus(302);

    // Check specific redirect to the Truck list
    $response->assertRedirect(route('trucks.index'));
});

test('truck profile can be fetched', function () {
    // Arrange: Create an authenticated user
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Act: Send GET request to fetch the Truck profile
    $response = $this->actingAs($user)
        ->get(route('truck.show', $truck->id));

    // Assert: Check that the response contains the expected data
    $response->assertStatus(200)
        ->assertInertia(
            fn ($page) => $page
                ->component('trucks/truck-profile')
                ->has(
                    'truck',
                    fn ($truckData) => $truckData
                        ->where('id', $truck->id)
                        ->etc()
                )
        );
});

test('trucks profile can be edited', function () {
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Prepare the updated Truck data
    $updatedTruckData = [
        'company_id' => $company->id,
        'license_plate' => 'AB-C-456',
        'identification_number' => 'ID654321',
        'next_major_inspection' => '2026-12-31',
        'next_safety_inspection' => '2026-12-31',
        'next_tachograph_inspection' => '2026-12-31',
        'additional_information' => 'No additional information',
    ];

    // Act: Send PUT request to update the Truck
    $response = $this->actingAs($user)
        ->put(route('truck.update', $truck->id), $updatedTruckData);

    // Assert: Check that the Truck data was updated in the database
    $this->assertDatabaseHas('trucks', $updatedTruckData);

    // Check that the response is a redirect (successful update)
    $response->assertStatus(302);

    // Check specific redirect to the Truck list
    $response->assertRedirect(route('trucks.index'));
});

test('a single truck can be deleted', function () {
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Act: Send DELETE request to delete the Truck
    $response = $this->actingAs($user)
        ->delete(route('truck.destroy', $truck->id));

    // Assert: Check that the Truck was deleted from the database
    $this->assertDatabaseMissing('trucks', [
        'id' => $truck->id,
    ]);

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Truck list
    $response->assertRedirect(route('trucks.index'));
});

test('multiple trucks can be deleted', function () {
    // Arrange: Create an authenticated user and multiple Trucks
    ['user' => $user, 'company' => $company, 'trucks' => $trucks] = createMultipleTrucksWithDependencies();

    // Act: Send DELETE request to delete all Trucks
    $response = $this->actingAs($user)
        ->delete(route('trucks.destroyMultiple'), [
            'truck_ids' => $trucks->pluck('id')->toArray(),
        ]);

    // Assert: Check that the Trucks were deleted from the database
    foreach ($trucks as $truck) {
        $this->assertDatabaseMissing('trucks', [
            'id' => $truck->id,
        ]);
    }

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Truck list
    $response->assertRedirect(route('trucks.index'));
});
