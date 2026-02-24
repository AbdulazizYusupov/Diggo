<?php

namespace App\Providers;

use App\Models\Group;
use App\Policies\AttendancePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');
        Paginator::useTailwind();
        Gate::policy(Group::class, AttendancePolicy::class);
    }
}
