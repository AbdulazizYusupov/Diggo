<?php

namespace App\Helpers;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

/**
 * RoutePermissionFilter
 * ─────────────────────────────────────────────────────────────────────────────
 * Single source of truth for deciding which routes should exist as Spatie
 * Permission records in the database.
 *
 * A route is included ONLY when ALL three conditions are met:
 *   1. Its name is not on the BLACKLIST (explicit exclusion list)
 *   2. Its name does not match any BLACKLIST_PATTERNS (prefix/wildcard rules)
 *   3. It passes through the  CheckPermission  middleware (auto-detection)
 *
 * Usage
 * ─────
 * // Get all routes that should be permissions:
 * $routes = RoutePermissionFilter::protectedRoutes();
 *
 * // Check a single route name:
 * RoutePermissionFilter::isBlacklisted('login')        // → true
 * RoutePermissionFilter::isBlacklisted('staffs.index') // → false
 * ─────────────────────────────────────────────────────────────────────────────
 */
class RoutePermissionFilter
{
    // ─────────────────────────────────────────────────────────────────────────
    // EXACT-MATCH BLACKLIST
    // Routes listed here will NEVER be stored as permissions, regardless of
    // which middleware group they belong to.
    // ─────────────────────────────────────────────────────────────────────────
    public const BLACKLIST = [
        // Auth (public)
        'login',
        'login.index',
        'logout',

        // Generic Laravel / framework internals
        'storage.local',

        // Shared auth-protected routes that are NOT permission-gated
        // (every logged-in user can reach them regardless of role)
        'dashboard',
        'statistics.index',
        'profile.show',
        'profile.update',
        'locale.set',
        'notifications.index',
        'notifications.read',
        'notifications.readAll',

        // Impersonate (super-admin only, controlled separately)
        'impersonate.index',
        'impersonate.start',
        'impersonate.leave',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // PREFIX / PATTERN BLACKLIST
    // Any route whose name STARTS WITH one of these prefixes is excluded.
    // ─────────────────────────────────────────────────────────────────────────
    public const BLACKLIST_PATTERNS = [
        'ignition.',
        '_ignition.',
        'sanctum.',
        'debugbar.',
        'telescope.',
        'horizon.',
        'livewire.',
        'filament.',
        'nova.',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // MIDDLEWARE that marks a route as "permission-gated"
    // ─────────────────────────────────────────────────────────────────────────
    public const PROTECTED_MIDDLEWARE = 'App\Http\Middleware\CheckPermission';

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Returns true when the given route name should be EXCLUDED from permissions.
     */
    public static function isBlacklisted(?string $name): bool
    {
        if (empty($name)) {
            return true;
        }

        // Exact match
        if (in_array($name, self::BLACKLIST, true)) {
            return true;
        }

        // Prefix / pattern match
        foreach (self::BLACKLIST_PATTERNS as $pattern) {
            if (str_starts_with($name, $pattern)) {
                return true;
            }
        }

        // Internal Laravel "generated" catch-all
        if (str_contains($name, 'generated')) {
            return true;
        }

        return false;
    }

    /**
     * Returns true when the route passes through CheckPermission middleware.
     * This is used as a secondary guard — if a route slips through the
     * blacklist but is not actually protected, it will still be excluded.
     */
    public static function isProtected(Route $route): bool
    {
        $middleware = $route->gatherMiddleware();

        foreach ($middleware as $m) {
            // Support both short alias ('check.permission') and FQCN
            if (
                $m === self::PROTECTED_MIDDLEWARE ||
                $m === 'check.permission' ||
                str_contains($m, 'CheckPermission')
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the filtered list of routes that SHOULD be stored as permissions.
     *
     * Each item is an array:
     *   ['name' => string, 'method' => string, 'uri' => string, 'action' => string]
     */
    public static function protectedRoutes(): array
    {
        $routes = [];

        foreach (RouteFacade::getRoutes() as $route) {
            $name = $route->getName();

            // Step 1 — blacklist check (fast, no middleware inspection needed)
            if (self::isBlacklisted($name)) {
                continue;
            }

            // Step 2 — middleware check (authoritative)
            if (! self::isProtected($route)) {
                continue;
            }

            $routes[] = [
                'name'   => $name,
                'method' => implode('|', $route->methods()),
                'uri'    => $route->uri(),
                'action' => $route->getActionName(),
            ];
        }

        // Sort alphabetically for consistent display
        usort($routes, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return $routes;
    }
}