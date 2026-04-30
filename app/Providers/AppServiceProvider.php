<?php

namespace App\Providers;

use App\Models\Group;
use App\Policies\AttendancePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // URL::forceScheme('https');
        Paginator::useTailwind();
        Gate::policy(Group::class, AttendancePolicy::class);
        View::composer('Layout.header', function ($view) {
            $view->with('current_lang', app()->getLocale());
            $view->with('locales', config('locales.supported'));
            if (Auth::check()) {
                $view->with([
                    'unreadCount'   => Auth::user()->unreadNotifications()->count(),
                    'notifications' => Auth::user()->notifications()->latest()->limit(15)->get(),
                ]);
            }
        });
    }
}
