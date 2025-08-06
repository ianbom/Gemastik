<?php

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:award-mission-badges')->everySecond();
Schedule::command('app:award-verified-report-badges')->everySecond();
Schedule::command('missions:set-on-progress')->everySecond();

