<?php

namespace App\Http\Controllers\Permission;

use App\Helpers\RoutePermissionFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    //  INDEX
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'all'); // all | synced | unsynced

        $query = Permission::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $permissions = $query->paginate(20)->withQueryString();

        // Only protected routes — blacklisted ones are already excluded
        $routes = RoutePermissionFilter::protectedRoutes();
        $stats  = $this->calculateStats($routes);

        return view('Permission.index', compact('permissions', 'search', 'filter', 'routes', 'stats'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  SYNC ROUTES  →  only adds permission-gated routes
    // ──────────────────────────────────────────────────────────────────────────

    public function syncRoutes()
    {
        try {
            // Only routes inside CheckPermission middleware, blacklist already applied
            $routes = RoutePermissionFilter::protectedRoutes();

            // Fetch existing permissions in one query
            $existing    = Permission::pluck('name')->flip(); // ['roles.index' => 0, ...]
            $toInsert    = [];
            $skipped     = 0;

            foreach ($routes as $route) {
                if (isset($existing[$route['name']])) {
                    $skipped++;
                    continue;
                }

                $toInsert[] = [
                    'name'       => $route['name'],
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (! empty($toInsert)) {
                Permission::insert($toInsert);
                app(PermissionRegistrar::class)->forgetCachedPermissions();
            }

            $synced = count($toInsert);

            return redirect()->route('permissions.index')
                ->with('success', "Muvaffaqiyatli! {$synced} ta yangi ruxsat qo'shildi. {$skipped} ta allaqachon mavjud.");

        } catch (\Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  PRUNE ORPHANED  →  removes permissions that are now blacklisted or
    //                     no longer exist as protected routes
    // ──────────────────────────────────────────────────────────────────────────

    public function pruneOrphaned()
    {
        try {
            $validRouteNames = collect(RoutePermissionFilter::protectedRoutes())
                ->pluck('name')
                ->flip(); // flip for O(1) lookup

            // Find every DB permission whose name is NOT in the valid set,
            // OR is explicitly blacklisted
            $orphaned = Permission::all()->filter(function (Permission $p) use ($validRouteNames) {
                return
                    RoutePermissionFilter::isBlacklisted($p->name) ||
                    ! isset($validRouteNames[$p->name]);
            });

            $skipped  = [];
            $deleted  = 0;

            foreach ($orphaned as $permission) {
                // Safety: do NOT delete permissions that are still assigned to roles
                if ($permission->roles()->count() > 0) {
                    $skipped[] = $permission->name;
                    continue;
                }

                $permission->delete();
                $deleted++;
            }

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $message = "{$deleted} ta keraksiz ruxsat o'chirildi.";
            if (! empty($skipped)) {
                $message .= ' ' . count($skipped) . " ta ruxsat rollarga biriktirilganligi sababli o'chirilmadi: "
                    . implode(', ', array_slice($skipped, 0, 5))
                    . (count($skipped) > 5 ? '...' : '');
            }

            return redirect()->route('permissions.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  BULK DELETE
    // ──────────────────────────────────────────────────────────────────────────

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $permissions = Permission::whereIn('id', $request->permissions)->get();

            foreach ($permissions as $permission) {
                if ($permission->roles()->count() > 0) {
                    return redirect()->route('permissions.index')
                        ->with('error', "'{$permission->name}' ruxsati rollarga biriktirilgan, o'chirib bo'lmaydi.");
                }
            }

            Permission::whereIn('id', $request->permissions)->delete();
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('permissions.index')
                ->with('success', count($request->permissions) . " ta ruxsat o'chirildi!");

        } catch (\Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  STATS  (used by index)
    // ──────────────────────────────────────────────────────────────────────────

    private function calculateStats(array $routes): array
    {
        $routeNames       = collect($routes)->pluck('name')->toArray();
        $permissionNames  = Permission::pluck('name')->toArray();

        return [
            'total_routes'      => count($routeNames),
            'total_permissions' => count($permissionNames),
            'synced'            => count(array_intersect($routeNames, $permissionNames)),
            'unsynced'          => count(array_diff($routeNames, $permissionNames)),
            'orphaned'          => count(array_diff($permissionNames, $routeNames)),
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  STANDARD CRUD  (update / destroy)
    // ──────────────────────────────────────────────────────────────────────────

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ], [
            'name.unique'    => 'Ushbu nomdagi ruxsatnoma allaqachon mavjud.',
            'name.required'  => "Ruxsatnoma nomi bo'sh bo'lishi mumkin emas.",
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->update(['name' => $request->input('name')]);
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('permissions.index')
                ->with('success', 'Ruxsatnoma muvaffaqiyatli yangilandi!');

        } catch (\Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->back()->with('success', "Permission muvaffaqiyatli o'chirildi");
    }

    // Unused stubs kept for resource route compatibility
    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
}