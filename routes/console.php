<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('charge:monthly')
//     ->monthlyOn(1, '06:00')  // Har oyning 1-sanasi, ertalab 6:00
//     ->withoutOverlapping()   // Parallel ishlamasligi uchun
//     ->runInBackground()
//     ->emailOutputOnFailure(config('mail.admin'));

Schedule::command('charge:monthly')
    ->everyMinute() // Har daqiqada ishga tushadi
    ->withoutOverlapping()
    ->emailOutputOnFailure(config('mail.admin'));
