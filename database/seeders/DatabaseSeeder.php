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
        // Create 10 User, with 10 Companies each, and each Company having 20 Trucks, Trailers, and Drivers
        $users = User::factory()->count(10)->create();

        foreach ($users as $user) {
            // Create 10 Company for each User
            $companies = Company::factory()->count(10)->create(['user_id' => $user->id]);

            foreach ($companies as $company) {
                // Create 20 Drivers, 20 Trucks, and 20 Trailers for each Company
                Driver::factory()->count(20)->create(['company_id' => $company->id]);
                Truck::factory()->count(20)->create(['company_id' => $company->id]);
                Trailer::factory()->count(20)->create(['company_id' => $company->id]);
            }
        }
    }
}
