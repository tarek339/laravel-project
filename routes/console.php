<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule commands to run daily at 9:00 AM
Schedule::command('app:check-drivers-command')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('app:check-trailers-command')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('app:check-trucks-command')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->runInBackground();
