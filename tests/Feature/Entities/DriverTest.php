<?php

use App\Models\Company;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\User;

function createDriverWithDependencies(): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);
    $driver = Driver::factory()->create(['company_id' => $company->id]);

    return compact('user', 'company', 'driver');
}

function createMultipleDriversWithDependencies(int $count = 3): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);
    $drivers = Driver::factory()->count($count)->create(['company_id' => $company->id]);

    return compact('user', 'company', 'drivers');
}

test('drivers can be fetched and displayed', function () {
    ['user' => $user, 'company' => $company, 'drivers' => $drivers] = createMultipleDriversWithDependencies();

    $this->actingAs($user)
        ->get('/drivers')
        ->assertInertia(
            fn ($page) => $page
                ->component('drivers/drivers-table')
                ->has('drivers')
        )
        ->assertStatus(200);
});

test('a driver can be added', function () {
    // Arrange: Create an authenticated user
    $user = User::factory()->create();

    // Create a Company for the Driver
    $company = Company::factory()->create(['user_id' => $user->id]);

    // Prepare the Driver data
    $driverData = [
        'company_id' => $company->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '1234567890',
        'license_number' => 'ABC123',
        'license_expiry_date' => '2025-12-31',
        'driver_card_number' => 'DC123456',
        'driver_card_expiry_date' => '2025-12-31',
        'driver_qualification_number' => 'DQ123456',
        'driver_qualification_expiry_date' => '2025-12-31',
        'street' => '123 Main St',
        'house_number' => '1A',
        'city' => 'Anytown',
        'postal_code' => '12345',
        'state' => 'CA',
        'country' => 'USA',
        'additional_information' => 'No additional information',
        'assigned_to' => null,
        'is_active' => false,
    ];

    // Act: Send POST request to add a Driver
    $response = $this->actingAs($user)
        ->post(route('driver.store'), $driverData);

    // Assert: Check that the Driver was saved to the database
    $this->assertDatabaseHas('drivers', $driverData);

    // Check that the response is a redirect (successful creation)
    $response->assertStatus(302);

    // Check specific redirect to the Driver list
    $response->assertRedirect(route('drivers.index'));
});

test('driver profile can be fetched', function () {
    // Arrange: Create an authenticated user
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();

    // Act: Send GET request to fetch the Driver profile
    $response = $this->actingAs($user)
        ->get(route('driver.show', $driver->id));

    // Assert: Check that the response contains the expected data
    $response->assertStatus(200)
        ->assertInertia(
            fn ($page) => $page
                ->component('drivers/driver-profile')
                ->has(
                    'driver',
                    fn ($driverData) => $driverData
                        ->where('id', $driver->id->toString())
                        ->etc()
                )
        );
});

test('drivers profile can be edited', function () {
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();

    // Prepare the updated Driver data
    $updatedDriverData = [
        'company_id' => $company->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane.doe@example.com',
        'phone' => '0987654321',
        'license_number' => 'XYZ789',
        'license_expiry_date' => '2026-12-31',
        'driver_card_number' => 'DC654321',
        'driver_card_expiry_date' => '2026-12-31',
        'driver_qualification_number' => 'DQ654321',
        'driver_qualification_expiry_date' => '2026-12-31',
        'street' => '456 Elm St',
        'house_number' => '2B',
        'city' => 'Othertown',
        'postal_code' => '54321',
        'state' => 'NY',
        'country' => 'USA',
        'additional_information' => 'No additional information',
        'assigned_to' => 'AB-C-123',
        'is_active' => true,
    ];

    // Act: Send PUT request to update the Driver
    $response = $this->actingAs($user)
        ->put(route('driver.update', $driver->id), $updatedDriverData);

    // Assert: Check that the Driver data was updated in the database
    $this->assertDatabaseHas('drivers', $updatedDriverData);

    // Check that the response is a redirect (successful update)
    $response->assertStatus(302);

    // Check specific redirect to the Driver list
    $response->assertRedirect(route('drivers.index'));
});

test('a single driver can be deleted', function () {
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();

    // Act: Send DELETE request to delete the Driver
    $response = $this->actingAs($user)
        ->delete(route('driver.destroy', $driver->id));

    // Assert: Check that the Driver was deleted from the database
    $this->assertDatabaseMissing('drivers', [
        'id' => $driver->id,
    ]);

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Driver list
    $response->assertRedirect(route('drivers.index'));
});

test('multiple drivers can be deleted', function () {
    // Arrange: Create an authenticated user and multiple Drivers
    ['user' => $user, 'company' => $company, 'drivers' => $drivers] = createMultipleDriversWithDependencies();

    // Act: Send DELETE request to delete all Drivers
    $response = $this->actingAs($user)
        ->delete(route('drivers.destroyMultiple'), [
            'driver_ids' => $drivers->pluck('id')->toArray(),
        ]);

    // Assert: Check that the Drivers were deleted from the database
    foreach ($drivers as $driver) {
        $this->assertDatabaseMissing('drivers', [
            'id' => $driver->id,
        ]);
    }

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Driver list
    $response->assertRedirect(route('drivers.index'));
});

test('should assign a truck to the driver from profile', function () {
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();
    $truck = Truck::factory()->create(['company_id' => $company->id]);

    // Act: Send POST request to assign the Truck to the Driver
    $response = $this->actingAs($user)
        ->post(route('driver.assignTruck', ['driver' => $driver->id]), [
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
        ->assertJson(['message' => 'Truck assigned to driver successfully.']);
});

test('should assign a truck to the driver from table', function () {
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();
    $truck = Truck::factory()->create(['company_id' => $company->id]);

    // Act: Send POST request to assign the Truck to the Driver
    $response = $this->actingAs($user)
        ->post(route('driver.assignTruckFromTable'), [
            'truck_id' => $truck->id,
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
        ->assertJson(['message' => 'Truck assigned to driver successfully.']);
});

test('should set the driver as active', function () {
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();

    // Act: Send POST request to set the Driver as active
    $response = $this->actingAs($user)
        ->post(route('driver.setActive', $driver->id));

    // Find the Driver
    $driver = Driver::find($driver->id);

    // Assert that the Driver is now active
    $this->assertTrue((bool) $driver->is_active);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Driver set to active successfully.']);
});

test('should set the driver as inactive', function () {
    ['user' => $user, 'company' => $company, 'driver' => $driver] = createDriverWithDependencies();

    // Act: Send POST request to set the Driver as inactive
    $response = $this->actingAs($user)
        ->post(route('driver.setInactive', $driver->id));

    // Find the Driver
    $driver = Driver::find($driver->id);

    // Assert that the Driver is now inactive
    $this->assertFalse((bool) $driver->is_active);

    // Check that the response is successful
    $response->assertStatus(200)
        ->assertJson(['message' => 'Driver set to inactive successfully.']);
});
