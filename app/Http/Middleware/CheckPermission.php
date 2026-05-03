<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
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

        $user = $this->permissionUser();
        $routeName = $request->route()->getName();

        // Admin yoki Super Admin rolini tekshirish (kichik harfda)
        $hasAdminRole = $user->roles->contains(function ($role) {
            return in_array(strtolower($role->name), ['admin', 'super admin', 'superadmin']);
        });

        // Admin/Super Admin foydalanuvchilarga barcha sahifalarga ruxsat
        if ($hasAdminRole) {
            return $next($request);
        }

        // Route nomi bo'lmasa, ruxsat berish
        if (!$routeName) {
            return $next($request);
        }

        // Admin/Super Admin bo'lmagan foydalanuvchilar uchun
        // Role, Permission, Role-Permission va Settings sahifalariga ruxsat yo'q
        $restrictedRoutes = [
            'roles.',
            'permissions.',
            'role-permissions.',
            'settings.'
        ];

        foreach ($restrictedRoutes as $restrictedRoute) {
            if (str_starts_with($routeName, $restrictedRoute)) {
                abort(403, 'Sizda bu sahifaga kirish huquqi yo\'q! Faqat Admin yoki Super Admin kirishlari mumkin.');
            }
        }

        // Boshqa sahifalar uchun permission tekshirish
        if (!$user->can($routeName)) {
            abort(403, 'Sizda bu sahifaga kirish huquqi yo\'q!');
        }

        return $next($request);
    }

    /**
     * Get the user for permission checks.
     * During impersonation, always use the impersonated user's permissions.
     * The admin must have exactly the same permissions and restrictions as the impersonated user.
     */
    private function permissionUser(): User
    {
        // Always return the currently authenticated user
        // This ensures that during impersonation, the impersonated user's permissions are enforced
        return Auth::user();
    }
}
