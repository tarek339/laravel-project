<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Trailer;
use App\Models\Truck;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 User, with 3 Companies each, and each Company having 10 Trucks, Trailers, and Drivers
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            // Create 3 Companies for each User
            $companies = Company::factory()->count(3)->create(['user_id' => $user->id]);

            foreach ($companies as $company) {
                // Create 10 Drivers, Trucks, and Trailers for each Company
                Driver::factory()->count(10)->create(['company_id' => $company->id]);
                Truck::factory()->count(10)->create(['company_id' => $company->id]);
                Trailer::factory()->count(10)->create(['company_id' => $company->id]);
            }
        }
    }
}
