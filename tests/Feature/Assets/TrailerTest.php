<?php

use App\Models\Company;
use App\Models\Trailer;
use App\Models\User;

function createTrailerWithDependencies(): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);
    $trailer = Trailer::factory()->create(['company_id' => $company->id]);

    return compact('user', 'company', 'trailer');
}

function createMultipleTrailersWithDependencies(int $count = 3): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);
    $trailers = Trailer::factory()->count($count)->create(['company_id' => $company->id]);

    return compact('user', 'company', 'trailers');
}

test('trailers can be fetched and displayed', function () {
    ['user' => $user, 'company' => $company, 'trailers' => $trailers] = createMultipleTrailersWithDependencies();

    $this->actingAs($user)
        ->get('/trailers')
        ->assertInertia(
            fn ($page) => $page
                ->component('trailers/trailers-table')
                ->has('trailers')
        )
        ->assertStatus(200);
});

test('a trailer can be added', function () {
    // Arrange: Create an authenticated user
    $user = User::factory()->create();

    // Create a Company for the Driver
    $company = Company::factory()->create(['user_id' => $user->id]);

    // Prepare the Trailer data
    $trailerData = [
        'company_id' => $company->id,
        'license_plate' => 'AB-C-123',
        'identification_number' => 'ID123456',
        'next_major_inspection' => '2025-12-31',
        'next_safety_inspection' => '2025-12-31',
        'additional_information' => 'No additional information',
    ];

    // Act: Send POST request to add a Trailer
    $response = $this->actingAs($user)
        ->post(route('trailer.store'), $trailerData);

    // Assert: Check that the Trailer was saved to the database
    $this->assertDatabaseHas('trailers', $trailerData);

    // Check that the response is a redirect (successful creation)
    $response->assertStatus(302);

    // Check specific redirect to the Trailer list
    $response->assertRedirect(route('trailers.index'));
});

test('trailer profile can be fetched', function () {
    // Arrange: Create an authenticated user
    ['user' => $user, 'company' => $company, 'trailer' => $trailer] = createTrailerWithDependencies();

    // Act: Send GET request to fetch the Trailer profile
    $response = $this->actingAs($user)
        ->get(route('trailer.show', $trailer->id));

    // Assert: Check that the response contains the expected data
    $response->assertStatus(200)
        ->assertInertia(
            fn ($page) => $page
                ->component('trailers/trailer-profile')
                ->has(
                    'trailer',
                    fn ($trailerData) => $trailerData
                        ->where('id', $trailer->id)
                        ->etc()
                )
        );
});

test('trailers profile can be edited', function () {
    ['user' => $user, 'company' => $company, 'trailer' => $trailer] = createTrailerWithDependencies();

    // Prepare the updated Trailer data
    $updatedTrailerData = [
        'company_id' => $company->id,
        'license_plate' => 'AB-C-456',
        'identification_number' => 'ID654321',
        'next_major_inspection' => '2026-12-31',
        'next_safety_inspection' => '2026-12-31',
        'additional_information' => 'No additional information',
    ];

    // Act: Send PUT request to update the Trailer
    $response = $this->actingAs($user)
        ->put(route('trailer.update', $trailer->id), $updatedTrailerData);

    // Assert: Check that the Trailer data was updated in the database
    $this->assertDatabaseHas('trailers', $updatedTrailerData);

    // Check that the response is a redirect (successful update)
    $response->assertStatus(302);

    // Check specific redirect to the Trailer list
    $response->assertRedirect(route('trailers.index'));
});

test('a single trailer can be deleted', function () {
    ['user' => $user, 'company' => $company, 'trailer' => $trailer] = createTrailerWithDependencies();

    // Act: Send DELETE request to delete the Trailer
    $response = $this->actingAs($user)
        ->delete(route('trailer.destroy', $trailer->id));

    // Assert: Check that the Trailer was deleted from the database
    $this->assertDatabaseMissing('trailers', [
        'id' => $trailer->id,
    ]);

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Trailer list
    $response->assertRedirect(route('trailers.index'));
});

test('multiple trailers can be deleted', function () {
    // Arrange: Create an authenticated user and multiple Trailers
    ['user' => $user, 'company' => $company, 'trailers' => $trailers] = createMultipleTrailersWithDependencies();

    // Act: Send DELETE request to delete all Trailers
    $response = $this->actingAs($user)
        ->delete(route('trailers.destroyMultiple'), [
            'trailer_ids' => $trailers->pluck('id')->toArray(),
        ]);

    // Assert: Check that the Trailers were deleted from the database
    foreach ($trailers as $trailer) {
        $this->assertDatabaseMissing('trailers', [
            'id' => $trailer->id,
        ]);
    }

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Trailer list
    $response->assertRedirect(route('trailers.index'));
});
