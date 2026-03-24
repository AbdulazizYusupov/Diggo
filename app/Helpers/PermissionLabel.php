<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Lang;

class PermissionLabel
{
    /**
     * Returns the label for a permission name.
     * Laravel automatically handles locale and fallback from lang/ folders.
     */
    public static function get(string $name): string
    {
        // lang/uz/permissions_map.php ichidan $name kalitini qidiradi
        $key = "permissions_map.{$name}";

        if (Lang::has($key)) {
            return __($key);
        }

        return self::prettify($name);
    }

    /**
     * Returns the label, or NULL if the permission is blacklisted.
     */
    public static function getOrNull(string $name): ?string
    {
        if (self::isExcluded($name)) {
            return null;
        }

        return self::get($name);
    }

    /**
     * Check if permission is blacklisted.
     */
    public static function isExcluded(string $name): bool
    {
        return RoutePermissionFilter::isBlacklisted($name);
    }

    /**
     * Returns a human-readable label for a module key.
     */
    public static function moduleLabel(string $module): string
    {
        $key = "module_labels.{$module}";

        if (Lang::has($key)) {
            return __($key);
        }

        return self::prettify($module);
    }

    /**
     * Splits a dotted permission name into its module and action parts.
     */
    public static function parse(string $name): array
    {
        $parts  = explode('.', $name);
        $action = array_pop($parts);
        $module = implode('.', $parts) ?: $name;

        return ['module' => $module, 'action' => $action];
    }

    /**
     * Standard prettify for missing keys.
     */
    private static function prettify(string $name): string
    {
        return ucwords(str_replace(['.', '-', '_'], ' ', $name));
    }
}