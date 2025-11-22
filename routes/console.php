<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('backup:run')->weekly()->at('12:00');
//Schedule::command('backup:run')->weekly()->at('12:00');
Schedule::command('complaints:reset')->dailyAt('12:00');
