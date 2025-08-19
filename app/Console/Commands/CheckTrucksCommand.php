<?php

namespace App\Console\Commands;

use App\Models\Truck;
use Illuminate\Console\Command;

class CheckTrucksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-trucks-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check trucks next major, safety, and tachograph inspection dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get trucks whose next major inspection is due in the next one or two months
        $nextMajorInspectionInOneMonth = Truck::whereDate('next_major_inspection', '=', now()->addDays(30)->toDateString())->get();
        $nextMajorInspectionInTwoMonths = Truck::whereDate('next_major_inspection', '=', now()->addDays(60)->toDateString())->get();

        $expiring_major_inspections = $this->checkTrucksMajorInspection($nextMajorInspectionInOneMonth, $nextMajorInspectionInTwoMonths);

        // Check trucks' safety inspection is due in the next or two months
        $nextSafetyInspectionInOneMonth = Truck::whereDate('next_safety_inspection', '=', now()->addDays(30)->toDateString())->get();
        $nextSafetyInspectionInTwoMonths = Truck::whereDate('next_safety_inspection', '=', now()->addDays(60)->toDateString())->get();

        $expiring_safety_inspections = $this->checkTrucksSafetyInspection($nextSafetyInspectionInOneMonth, $nextSafetyInspectionInTwoMonths);

        // Check trucks' tachograph inspection is due in the next or two months
        $nextTachographInspectionInOneMonth = Truck::whereDate('next_tachograph_inspection', '=', now()->addDays(30)->toDateString())->get();
        $nextTachographInspectionInTwoMonths = Truck::whereDate('next_tachograph_inspection', '=', now()->addDays(60)->toDateString())->get();

        $expiring_tachograph_inspections = $this->checkTrucksTachoInspection($nextTachographInspectionInOneMonth, $nextTachographInspectionInTwoMonths);

        $expiring_trucks = array_merge($expiring_major_inspections, $expiring_safety_inspections, $expiring_tachograph_inspections);

        print_r($expiring_trucks);
    }

    private function checkTrucksMajorInspection($nextMajorInspectionInOneMonth, $nextMajorInspectionInTwoMonths)
    {
        $trucks = [];

        foreach ($nextMajorInspectionInOneMonth as $truck) {
            $this->info("Truck {$truck->license_plate} next major inspection is next month - {$truck->next_major_inspection}");
            $trucks[] = [
                'license_plate' => $truck->license_plate,
                'next_major_inspection' => $truck->next_major_inspection,
            ];
        }

        foreach ($nextMajorInspectionInTwoMonths as $truck) {
            $this->info("Truck {$truck->license_plate} next major inspection is in two months - {$truck->next_major_inspection}");
            $trucks[] = [
                'license_plate' => $truck->license_plate,
                'next_major_inspection' => $truck->next_major_inspection,
            ];
        }

        return $trucks;
    }

    private function checkTrucksSafetyInspection(
        $nextSafetyInspectionInOneMonth,
        $nextSafetyInspectionInTwoMonths
    ) {
        $trucks = [];

        foreach ($nextSafetyInspectionInOneMonth as $truck) {
            $this->info("Truck {$truck->license_plate} next safety inspection is next month - {$truck->next_safety_inspection}");
            $trucks[] = [
                'license_plate' => $truck->license_plate,
                'next_safety_inspection' => $truck->next_safety_inspection,
            ];
        }

        foreach ($nextSafetyInspectionInTwoMonths as $truck) {
            $this->info("Truck {$truck->license_plate} next safety inspection is in two months - {$truck->next_safety_inspection}");
            $trucks[] = [
                'license_plate' => $truck->license_plate,
                'next_safety_inspection' => $truck->next_safety_inspection,
            ];
        }

        return $trucks;
    }

    private function checkTrucksTachoInspection(
        $nextTachographInspectionInOneMonth,
        $nextTachographInspectionInTwoMonths
    ) {
        $trucks = [];

        foreach ($nextTachographInspectionInOneMonth as $truck) {
            $this->info("Truck {$truck->license_plate} next tachograph inspection is next month - {$truck->next_tachograph_inspection}");
            $trucks[] = [
                'license_plate' => $truck->license_plate,
                'next_tachograph_inspection' => $truck->next_tachograph_inspection,
            ];
        }

        foreach ($nextTachographInspectionInTwoMonths as $truck) {
            $this->info("Truck {$truck->license_plate} next tachograph inspection is in two months - {$truck->next_tachograph_inspection}");
            $trucks[] = [
                'license_plate' => $truck->license_plate,
                'next_tachograph_inspection' => $truck->next_tachograph_inspection,
            ];
        }

        return $trucks;
    }
}
