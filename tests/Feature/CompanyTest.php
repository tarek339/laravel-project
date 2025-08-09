<?php

use App\Models\Company;
use App\Models\User;

function createCompanyWithDependencies(): array
{
    $user = User::factory()->create();
    $company = Company::factory()->create(['user_id' => $user->id]);

    return compact('user', 'company');
}

function createMultipleCompaniesWithDependencies(int $count = 3): array
{
    $user = User::factory()->create();
    $companies = Company::factory()->count($count)->create(['user_id' => $user->id]);

    return compact('user', 'companies');
}

test('companies can be fetched and displayed', function () {
    $user = User::factory()->create();
    Company::factory()->count(3)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get('/companies')
        ->assertInertia(
            fn ($page) => $page
                ->component('companies/companies-table')
                ->has('companies')
        )
        ->assertStatus(200);
});

test('a company can be added', function () {
    // Arrange: Create an authenticated user
    $user = User::factory()->create();

    // Prepare the Company data
    $companyData = [
        'user_id' => $user->id,
        'name' => 'Test Company',
        'phone' => '123-456-7890',
        'email' => 'test@example.com',
        'street' => '123 Test St',
        'house_number' => '1A',
        'city' => 'Test City',
        'state' => 'Test State',
        'zip' => '12345',
        'country' => 'Test Country',
        'website' => 'https://www.testcompany.com',
        'authorization_number' => 'AUTH123456',
        'authorization_number_expiry_date' => '2025-12-31',
    ];

    // Act: Send POST request to add a Company
    $response = $this->actingAs($user)
        ->post(route('company.store'), $companyData);

    // Assert: Check that the Company was saved to the database
    $this->assertDatabaseHas('companies', $companyData);

    // Check that the response is a redirect (successful creation)
    $response->assertStatus(302);

    // Check specific redirect to the Company list
    $response->assertRedirect(route('companies.index'));
});

test('company profile can be fetched', function () {
    // Arrange: Create an authenticated user
    ['user' => $user, 'company' => $company] = createCompanyWithDependencies();

    // Act: Send GET request to fetch the Company profile
    $response = $this->actingAs($user)
        ->get(route('company.show', $company->id));

    // Assert: Check that the response contains the expected data
    $response->assertStatus(200)
        ->assertInertia(
            fn ($page) => $page
                ->component('companies/company-profile')
                ->has(
                    'company',
                    fn ($companyData) => $companyData
                        ->where('id', $company->id)
                        ->etc()
                )
        );
});

test('companies profile can be edited', function () {
    $user = User::factory()->create();
    Company::factory()->count(3)->create(['user_id' => $user->id]);

    $updateCompanyData = [
        'user_id' => $user->id,
        'name' => 'Test Company',
        'phone' => '123-456-7890',
        'email' => 'test@example.com',
        'street' => '123 Test St',
        'house_number' => '1A',
        'city' => 'Test City',
        'state' => 'Test State',
        'zip' => '12345',
        'country' => 'Test Country',
        'website' => 'https://www.testcompany.com',
        'authorization_number' => 'AUTH123456',
        'authorization_number_expiry_date' => '2025-12-31',
    ];

    // Act: Send PUT request to update the Company
    $response = $this->actingAs($user)
        ->put(route('company.update', $user->id), $updateCompanyData);

    // Assert: Check that the Company data was updated in the database
    $this->assertDatabaseHas('companies', $updateCompanyData);

    // Check that the response is a redirect (successful update)
    $response->assertStatus(302);

    // Check specific redirect to the Driver list
    $response->assertRedirect(route('companies.index'));
});

test('a single company can be deleted', function () {
    // Arrange: Create an authenticated user and a company
    $user = User::factory()->create();
    ['user' => $user, 'company' => $company] = createCompanyWithDependencies();

    // Act: Send DELETE request to delete the Company
    $response = $this->actingAs($user)
        ->delete(route('company.destroy', $company->id));

    // Assert: Check that the Company was deleted from the database
    $this->assertDatabaseMissing('companies', ['id' => $company->id]);

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Company list
    $response->assertRedirect(route('companies.index'));
});

test('multiple companies can be deleted', function () {
    // Arrange: Create an authenticated user and multiple companies
    $user = User::factory()->create();
    Company::factory()->count(3)->create(['user_id' => $user->id]);

    // Act: Send DELETE request to delete all Companies
    $response = $this->actingAs($user)
        ->delete(route('companies.destroyMultiple'), [
            'company_ids' => Company::pluck('id')->toArray(),
        ]);

    // Assert: Check that the Companies were deleted from the database
    foreach (Company::all() as $company) {
        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }

    // Check that the response is a redirect (successful deletion)
    $response->assertStatus(302);

    // Check specific redirect to the Company list
    $response->assertRedirect(route('companies.index'));
});
