<?php

namespace App\Console\Commands;

use App\Models\Driver;
use Illuminate\Console\Command;

class CheckDriversCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-drivers-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check drivers licenses, cards, and qualifications expiry dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get drivers whose licenses expire in the next one or two months
        $licenseExpiresInOneMonth = Driver::whereDate('license_expiry_date', '=', now()->addDays(30)->toDateString())->get();
        $licenseExpiresInTwoMonth = Driver::whereDate('license_expiry_date', '=', now()->addDays(60)->toDateString())->get();

        $this->checkDriversLicense($licenseExpiresInOneMonth, $licenseExpiresInTwoMonth);

        // Get drivers whose cards expire in the next one or two months
        $driversCardExpiresInOneMonth = Driver::whereDate('driver_card_expiry_date', '=', now()->addDays(30)->toDateString())->get();
        $driversCardExpiresInTwoMonth = Driver::whereDate('driver_card_expiry_date', '=', now()->addDays(60)->toDateString())->get();

        $this->checkDriversCard($driversCardExpiresInOneMonth, $driversCardExpiresInTwoMonth);

        $driversQualificationExpiresInOneMonth = Driver::whereDate('driver_qualification_expiry_date', '=', now()->addDays(30)->toDateString())->get();
        $driversQualificationExpiresInTwoMonth = Driver::whereDate('driver_qualification_expiry_date', '=', now()->addDays(60)->toDateString())->get();

        $this->checkQualification($driversQualificationExpiresInOneMonth, $driversQualificationExpiresInTwoMonth);
    }

    /**
     * Check drivers' licenses and return an array of drivers with their details.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $licenseExpiresInOneMonth
     * @param  \Illuminate\Database\Eloquent\Collection  $licenseExpiresInTwoMonth
     * @return array
     */
    private function checkDriversLicense($licenseExpiresInOneMonth, $licenseExpiresInTwoMonth)
    {
        $drivers = [];

        foreach ($licenseExpiresInOneMonth as $driver) {
            $this->info("Driver {$driver->first_name} {$driver->last_name} license expires in one month");
            $drivers[] = [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'license_number' => $driver->license_number,
                'license_expiry_date' => $driver->license_expiry_date,
            ];
        }

        foreach ($licenseExpiresInTwoMonth as $driver) {
            $this->info("Driver {$driver->first_name} {$driver->last_name} license expires in two months");
            $drivers[] = [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'license_number' => $driver->license_number,
                'license_expiry_date' => $driver->license_expiry_date,
            ];
        }

        return $drivers;
    }

    /**
     * Check drivers' cards and return an array of drivers with their details.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $driversCardExpiresInOneMonth
     * @param  \Illuminate\Database\Eloquent\Collection  $driversCardExpiresInTwoMonth
     * @return array
     */
    private function checkDriversCard($driversCardExpiresInOneMonth, $driversCardExpiresInTwoMonth)
    {
        $drivers = [];

        foreach ($driversCardExpiresInOneMonth as $driver) {
            $this->info("Driver {$driver->first_name} {$driver->last_name} driver card expires in one month");
            $drivers[] = [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'driver_card_number' => $driver->driver_card_number,
                'driver_card_expiry_date' => $driver->driver_card_expiry_date,
            ];
        }

        foreach ($driversCardExpiresInTwoMonth as $driver) {
            $this->info("Driver {$driver->first_name} {$driver->last_name} driver card expires in two months");
            $drivers[] = [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'driver_card_number' => $driver->driver_card_number,
                'driver_card_expiry_date' => $driver->driver_card_expiry_date,
            ];
        }

        return $drivers;
    }

    /**
     * Check drivers' qualifications and return an array of drivers with their details.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $driversQualificationExpiresInOneMonth
     * @param  \Illuminate\Database\Eloquent\Collection  $driversQualificationExpiresInTwoMonth
     * @return array
     */
    private function checkQualification($driversQualificationExpiresInOneMonth, $driversQualificationExpiresInTwoMonth)
    {
        $drivers = [];

        foreach ($driversQualificationExpiresInOneMonth as $driver) {
            $this->info("Driver {$driver->first_name} {$driver->last_name} driver qualification expires in one month");
            $drivers[] = [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'driver_qualification_number' => $driver->driver_qualification_number,
                'driver_qualification_expiry_date' => $driver->driver_qualification_expiry_date,
            ];
        }

        foreach ($driversQualificationExpiresInTwoMonth as $driver) {
            $this->info("Driver {$driver->first_name} {$driver->last_name} driver qualification expires in two months");
            $drivers[] = [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'email' => $driver->email,
                'driver_qualification_number' => $driver->driver_qualification_number,
                'driver_qualification_expiry_date' => $driver->driver_qualification_expiry_date,
            ];
        }

        return $drivers;
    }
}
