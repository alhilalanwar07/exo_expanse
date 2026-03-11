<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Process queued jobs every minute (alternative to Supervisor)
Schedule::command('queue:work --stop-when-empty --tries=2 --timeout=180')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
