<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic appointment status updates
Schedule::command('appointments:update-past')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Schedule demo data reset every hour (only if demo tenant exists)
Schedule::command('demo:reset --force')
    ->hourly()
    ->when(function () {
        return \App\Models\Tenant::where('subdomain', 'demo')->exists();
    })
    ->withoutOverlapping()
    ->runInBackground();
