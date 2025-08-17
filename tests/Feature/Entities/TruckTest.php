<?php

use App\Models\Company;
use App\Models\Driver;
use App\Models\Trailer;
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
        'assigned_to_trailer' => null,
        'assigned_to_driver' => null,
        'is_active' => false,
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
        'assigned_to_trailer' => 'AB-C-654',
        'assigned_to_driver' => 'Jon Doe',
        'is_active' => true,
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

test('should assign a driver to the truck from profile', function () {
    // Arrange: Create an authenticated user and a Truck
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Create a Driver for the Truck
    $driver = Driver::factory()->create(['company_id' => $company->id]);

    // Act: Send POST request to assign the Driver to the Truck
    $response = $this->actingAs($user)
        ->post(route('truck.assignDriver', $truck->id), [
            'driver_id' => $driver->id,
        ]);

    // Find both Entities
    $driver = Driver::find($driver->id);
    $truck = Truck::find($truck->id);

    // Assert that the relationships are correctly set
    $this->assertEquals($truck->license_plate, $driver->assigned_to);
    $this->assertEquals($driver->first_name.' '.$driver->last_name, $truck->assigned_to_driver);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Driver assigned to truck successfully.']);
});

test('should assign a driver to the truck from table', function () {
    // Arrange: Create an authenticated user and a Truck
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Create a Driver for the Truck
    $driver = Driver::factory()->create(['company_id' => $company->id]);

    // Act: Send POST request to assign the Driver to the Truck
    $response = $this->actingAs($user)
        ->post(route('truck.assignDriverFromTable'), [
            'driver_id' => $driver->id,
            'truck_id' => $truck->id,
        ]);

    // Find both Entities
    $driver = Driver::find($driver->id);
    $truck = Truck::find($truck->id);

    // Assert that the relationships are correctly set
    $this->assertEquals($truck->license_plate, $driver->assigned_to);
    $this->assertEquals($driver->first_name.' '.$driver->last_name, $truck->assigned_to_driver);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Driver assigned to truck successfully.']);
});

test('should assign a trailer to the truck from profile', function () {
    // Arrange: Create an authenticated user and a Truck
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Create a Trailer for the Truck
    $trailer = Trailer::factory()->create(['company_id' => $company->id]);

    // Act: Send POST request to assign the Trailer to the Truck
    $response = $this->actingAs($user)
        ->post(route('truck.assignTrailer', $truck->id), [
            'trailer_id' => $trailer->id,
        ]);

    // Find both Entities
    $truck = Truck::find($truck->id);
    $trailer = Trailer::find($trailer->id);

    // Assert that the relationships are correctly set
    $this->assertEquals($truck->license_plate, $trailer->assigned_to);
    $this->assertEquals($trailer->license_plate, $truck->assigned_to_trailer);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Trailer assigned to truck successfully.']);
});

test('should assign a trailer to the truck from table', function () {
    // Arrange: Create an authenticated user and a Truck
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Create a Trailer for the Truck
    $trailer = Trailer::factory()->create(['company_id' => $company->id]);

    // Act: Send POST request to assign the Trailer to the Truck
    $response = $this->actingAs($user)
        ->post(route('truck.assignTrailerFromTable'), [
            'trailer_id' => $trailer->id,
            'truck_id' => $truck->id,
        ]);

    // Find both Entities
    $truck = Truck::find($truck->id);
    $trailer = Trailer::find($trailer->id);

    // Assert that the relationships are correctly set
    $this->assertEquals($truck->license_plate, $trailer->assigned_to);
    $this->assertEquals($trailer->license_plate, $truck->assigned_to_trailer);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Trailer assigned to truck successfully.']);
});

test('should set the truck as active', function () {
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Act: Send POST request to set the Truck as active
    $response = $this->actingAs($user)
        ->post(route('truck.setActive', $truck->id));

    // Find the Truck
    $truck = Truck::find($truck->id);

    // Assert that the Truck is now active
    $this->assertTrue((bool) $truck->is_active);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Truck set to active successfully.']);
});

test('should set the truck as inactive', function () {
    ['user' => $user, 'company' => $company, 'truck' => $truck] = createTruckWithDependencies();

    // Act: Send POST request to set the Truck as inactive
    $response = $this->actingAs($user)
        ->post(route('truck.setInactive', $truck->id));

    // Find the Truck
    $truck = Truck::find($truck->id);

    // Assert that the Truck is now inactive
    $this->assertFalse((bool) $truck->is_active);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Truck set to inactive successfully.']);
});
