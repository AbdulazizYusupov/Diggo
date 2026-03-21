<?php

namespace Database\Seeders;

use App\Helpers\RoutePermissionFilter;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * PermissionSeeder
 * ─────────────────────────────────────────────────────────────────────────────
 * Idempotent seeder — safe to run multiple times.
 *
 *  php artisan db:seed --class=PermissionSeeder
 *
 * What it does
 * ────────────
 *  1. Reads every protected route via RoutePermissionFilter::protectedRoutes()
 *     (blacklisted + non-CheckPermission routes are already excluded)
 *  2. Inserts missing permissions (bulk, one query)
 *  3. Deletes "orphaned" permissions — those that are no longer valid routes
 *     AND are not assigned to any role (safe to remove)
 *  4. Clears Spatie's permission cache
 * ─────────────────────────────────────────────────────────────────────────────
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Reset cached roles and permissions ─────────────────────────────
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ── 2. Collect all valid protected route names ────────────────────────
        $protectedRoutes = RoutePermissionFilter::protectedRoutes();
        $validNames      = collect($protectedRoutes)->pluck('name');

        $this->command->info('📋 Protected routes found: ' . $validNames->count());

        // ── 3. Insert missing permissions (bulk, one query) ───────────────────
        $existing = Permission::pluck('name')->flip(); // O(1) lookup map

        $toInsert = $validNames
            ->filter(fn ($name) => ! isset($existing[$name]))
            ->map(fn ($name) => [
                'name'       => $name,
                'guard_name' => 'web',
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ])
            ->values()
            ->all();

        if (! empty($toInsert)) {
            Permission::insert($toInsert);
            $this->command->info('✅ Inserted ' . count($toInsert) . ' new permissions.');
        } else {
            $this->command->info('ℹ️  No new permissions to insert.');
        }

        // ── 4. Prune orphaned permissions ─────────────────────────────────────
        // An orphaned permission is one that:
        //   (a) no longer maps to a protected route, OR
        //   (b) is explicitly on the BLACKLIST
        // We only delete it if it has NOT been assigned to any role,
        // to avoid silently breaking existing role configurations.
        $validFlip   = $validNames->flip();
        $allPerms    = Permission::with('roles')->get();
        $pruned      = 0;
        $skipped     = [];

        foreach ($allPerms as $perm) {
            $isOrphaned =
                RoutePermissionFilter::isBlacklisted($perm->name) ||
                ! isset($validFlip[$perm->name]);

            if (! $isOrphaned) {
                continue;
            }

            if ($perm->roles->isNotEmpty()) {
                $skipped[] = $perm->name;
                continue; // Don't touch assigned permissions
            }

            $perm->delete();
            $pruned++;
        }

        if ($pruned > 0) {
            $this->command->warn("🗑  Pruned {$pruned} orphaned permission(s).");
        }

        if (! empty($skipped)) {
            $this->command->warn(
                '⚠️  Skipped ' . count($skipped) . ' orphaned permission(s) still assigned to roles:'
            );
            foreach (array_slice($skipped, 0, 10) as $s) {
                $this->command->line("     - {$s}");
            }
            if (count($skipped) > 10) {
                $this->command->line('     ... and ' . (count($skipped) - 10) . ' more.');
            }
            $this->command->warn('   Manually review and re-assign these roles before deleting.');
        }

        // ── 5. Refresh cache ──────────────────────────────────────────────────
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->command->info('🔄 Permission cache cleared.');

        // ── 6. Summary ────────────────────────────────────────────────────────
        $total = Permission::count();
        $this->command->info("✔  Done. Total permissions in DB: {$total}");
    }
}