<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class HandleImpersonation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('impersonate.active')) {
            View::share('impersonating', true);
            View::share('impersonate_target_name', session('impersonate.target_name'));
            View::share('impersonate_target_role', session('impersonate.target_role'));
            View::share('impersonate_original_name', session('impersonate.original_name'));
        } else {
            View::share('impersonating', false);
        }
        return $next($request);
    }
}
