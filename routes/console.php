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
