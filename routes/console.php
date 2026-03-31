<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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

Schedule::command('charge:monthly')
    ->monthlyOn(1, '06:00')   // Har oy 1-sanasi, ertalab 06:00 da
    ->withoutOverlapping()     // Bir vaqtda ikki marta ishlamasin
    ->runInBackground()        // Background da ishlaydi
    ->onSuccess(function () {
        Log::info('✅ Monthly charge cron muvaffaqiyatli yakunlandi: ' . now());
    })
    ->onFailure(function () {
        Log::error('❌ Monthly charge cron xatolik bilan tugadi: ' . now());
    });
