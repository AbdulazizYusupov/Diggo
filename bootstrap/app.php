<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\HandleImpersonation;
use App\Http\Middleware\SetLocale;
use App\Services\Payment\MonthlyChargeService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => CheckPermission::class,
            'director'   => \App\Http\Middleware\EnsureDirector::class,
        ]);
        $middleware->appendToGroup('web', [
            HandleImpersonation::class,
            SetLocale::class
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Run monthly charge on 1st of each month at 2 AM
        $schedule->call(function () {
            app(MonthlyChargeService::class)->runMonthlyCharge(Carbon::now()->startOfMonth());
        })->monthlyOn(1, '02:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
