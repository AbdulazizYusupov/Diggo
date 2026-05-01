<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login.index')->with('error', 'Iltimos, tizimga kiring!');
        }

        $user = Auth::user();

        if (!method_exists($user, 'hasRole') || !$user->hasRole('director') || !$user->director) {
            abort(403, 'Sizda bu sahifaga kirish huquqi yo\'q!');
        }

        return $next($request);
    }
}
