<?php

namespace App\Helpers;

/**
 * PermissionLabel
 * ─────────────────────────────────────────────────────────────────────────────
 * Translates a technical permission slug into a user-friendly Uzbek label.
 *
 * Key methods
 * ───────────
 *  PermissionLabel::get('roles.index')         → "Rollar ro'yxati"
 *  PermissionLabel::getOrNull('login')          → null  (blacklisted)
 *  PermissionLabel::isExcluded('login')         → true
 *  PermissionLabel::moduleLabel('groups')       → "Guruhlar"
 * ─────────────────────────────────────────────────────────────────────────────
 */
class PermissionLabel
{
    // ──────────────────────────────────────────────────────────────────────────
    //  PUBLIC API
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Returns the Uzbek label for a permission name.
     * Falls back to a prettified version of the slug if not found in the map.
     * Never returns null — safe to echo directly in Blade.
     */
    public static function get(string $name): string
    {
        $map = config('permissions_map', []);

        return $map[$name] ?? self::prettify($name);
    }

    /**
     * Returns the Uzbek label, or NULL if the permission is blacklisted.
     * Use this in Blade when you want to skip rendering excluded permissions:
     *
     *   @if ($label = PermissionLabel::getOrNull($permission->name))
     *       {{ $label }}
     *   @endif
     */
    public static function getOrNull(string $name): ?string
    {
        if (self::isExcluded($name)) {
            return null;
        }

        return self::get($name);
    }

    /**
     * Returns true when the permission name is on the RoutePermissionFilter
     * blacklist — meaning it should not appear in the UI or be stored in DB.
     */
    public static function isExcluded(string $name): bool
    {
        return RoutePermissionFilter::isBlacklisted($name);
    }

    /**
     * Returns a human-readable Uzbek label for a module key.
     * Used in grouped permission views (edit.blade / show.blade).
     */
    public static function moduleLabel(string $module): string
    {
        return self::moduleLabelMap()[$module]
            ?? ucwords(str_replace(['.', '-', '_'], ' ', $module));
    }

    /**
     * Splits a dotted permission name into its module and action parts.
     *
     * parse('groups.students.destroy')
     *   => ['module' => 'groups.students', 'action' => 'destroy']
     */
    public static function parse(string $name): array
    {
        $parts  = explode('.', $name);
        $action = array_pop($parts);
        $module = implode('.', $parts) ?: $name;

        return ['module' => $module, 'action' => $action];
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    private static function prettify(string $name): string
    {
        return ucwords(str_replace(['.', '-', '_'], ' ', $name));
    }

    private static function moduleLabelMap(): array
    {
        return [
            'staffs'                    => 'Xodimlar',
            'directors'                 => 'Direktorlar',
            'teachers'                  => "O'qituvchilar",
            'students'                  => "O'quvchilar",
            'roles'                     => 'Rollar',
            'permissions'               => 'Ruxsatlar',
            'role-permissions'          => 'Rol ruxsatlari',
            'settings'                  => 'Sozlamalar',
            'schools'                   => 'Maktablar',
            'courses'                   => 'Kurslar',
            'groups'                    => 'Guruhlar',
            'groups.students'           => "Guruh -> O'quvchilar",
            'groups.teachers'           => "Guruh -> O'qituvchilar",
            'group-teachers'            => "Guruh o'qituvchilari",
            'payments'                  => "To'lovlar",
            'activities'                => 'Faoliyatlar',
            'activityresults'           => 'Faoliyat natijalari',
            'attendance'                => 'Davomat',
            'lessons'                   => 'Darslar',
            'transactions'              => 'Tranzaksiyalar',
            'coin-transactions'         => 'Tanga tranzaksiyalari',
            'director'                  => 'Direktor paneli',
            'director.commissions'      => 'Direktor - Komissiyalar',
            'director.groups'           => 'Direktor - Guruhlar',
            'director.reports'          => 'Direktor - Hisobotlar',
            'director.students'         => "Direktor - O'quvchilar",
            'student'                   => "O'quvchi paneli",
            'student.shop'              => "O'quvchi do'koni",
            'student.profile'           => "O'quvchi profili",
            'student.activities'        => "O'quvchi faoliyatlari",
            'student.attendance'        => "O'quvchi davomati",
            'student.schedule'          => "O'quvchi jadvali",
            'student.coins'             => "O'quvchi tanglari",
            'student.payments'          => "O'quvchi to'lovlari",
            'student.groups'            => "O'quvchi guruhlari",
            'teacher'                   => "O'qituvchi paneli",
            'teacher.groups'            => "O'qituvchi guruhlari",
            'teacher.groups.lessons'    => "O'qituvchi darslari",
            'admin.shop.categories'     => "Do'kon kategoriyalari",
            'admin.shop.products'       => "Do'kon mahsulotlari",
            'admin.shop.orders'         => "Do'kon buyurtmalari",
            'help'                      => 'Yordam',
            'activity-logs'             => 'Faoliyat jurnali',
            'login-attempts'            => 'Kirish urinishlari',
            'trashed-users'             => "O'chirilgan foydalanuvchilar",
        ];
    }
}