<?php

namespace App\Console\Commands;

use App\Models\Trailer;
use Illuminate\Console\Command;

class CheckTrailersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-trailers-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check trailers next major and safety inspection dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get trailers whose next major inspection is due in the next one or two months
        $nextMajorInspectionInOneMonth = Trailer::whereDate('next_major_inspection', '=', now()->addDays(30)->toDateString())->get();
        $nextMajorInspectionInTwoMonths = Trailer::whereDate('next_major_inspection', '=', now()->addDays(60)->toDateString())->get();

        $this->checkTrailersMajorInspection($nextMajorInspectionInOneMonth, $nextMajorInspectionInTwoMonths);

        // Check trailers' safety inspection is due in the next or two months
        $nextSafetyInspectionInOneMonth = Trailer::whereDate('next_safety_inspection', '=', now()->addDays(30)->toDateString())->get();
        $nextSafetyInspectionInTwoMonths = Trailer::whereDate('next_safety_inspection', '=', now()->addDays(60)->toDateString())->get();

        $this->checkTrailersSafetyInspection($nextSafetyInspectionInOneMonth, $nextSafetyInspectionInTwoMonths);
    }

    private function checkTrailersMajorInspection($nextMajorInspectionInOneMonth, $nextMajorInspectionInTwoMonths)
    {
        $trailers = [];

        foreach ($nextMajorInspectionInOneMonth as $trailer) {
            $this->info("Trailer {$trailer->license_plate} next major inspection is next month - {$trailer->next_major_inspection}");
            $trailers[] = [
                'license_plate' => $trailer->license_plate,
                'next_major_inspection' => $trailer->next_major_inspection,
            ];
        }

        foreach ($nextMajorInspectionInTwoMonths as $trailer) {
            $this->info("Trailer {$trailer->license_plate} next major inspection is in two months - {$trailer->next_major_inspection}");
            $trailers[] = [
                'license_plate' => $trailer->license_plate,
                'next_major_inspection' => $trailer->next_major_inspection,
            ];
        }

        return $trailers;
    }

    private function checkTrailersSafetyInspection(
        $nextSafetyInspectionInOneMonth,
        $nextSafetyInspectionInTwoMonths
    ) {
        $trailers = [];

        foreach ($nextSafetyInspectionInOneMonth as $trailer) {
            $this->info("Trailer {$trailer->license_plate} next safety inspection is next month - {$trailer->next_safety_inspection}");
            $trailers[] = [
                'license_plate' => $trailer->license_plate,
                'next_safety_inspection' => $trailer->next_safety_inspection,
            ];
        }

        foreach ($nextSafetyInspectionInTwoMonths as $trailer) {
            $this->info("Trailer {$trailer->license_plate} next safety inspection is in two months - {$trailer->next_safety_inspection}");
            $trailers[] = [
                'license_plate' => $trailer->license_plate,
                'next_safety_inspection' => $trailer->next_safety_inspection,
            ];
        }

        return $trailers;
    }
}
