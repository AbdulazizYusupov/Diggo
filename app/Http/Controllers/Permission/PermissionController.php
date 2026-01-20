<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $filter = $request->input('filter', 'all'); // all, synced, unsynced

        // Get all permissions from database
        $query = Permission::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $permissions = $query->paginate(20)->withQueryString();

        // Get all routes from application
        $routes = $this->getApplicationRoutes();

        // Calculate statistics
        $stats = $this->calculateStats($routes);

        return view('Permission.index', compact('permissions', 'search', 'filter', 'routes', 'stats'));
    }
    public function syncRoutes()
    {
        try {
            $routes = $this->getApplicationRoutes();
            $synced = 0;
            $skipped = 0;

            foreach ($routes as $route) {
                // Check if permission already exists
                $exists = Permission::where('name', $route['name'])->exists();

                if (!$exists) {
                    Permission::create([
                        'name' => $route['name'],
                        'guard_name' => 'web'
                    ]);
                    $synced++;
                } else {
                    $skipped++;
                }
            }

            return redirect()->route('permissions.index')->with('success', "Muvaffaqiyatli! {$synced} ta yangi ruxsat qo'shildi. {$skipped} ta allaqachon mavjud.");
        } catch (\Exception $e) {
            return redirect()->route('permissions.index')->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $permissions = Permission::whereIn('id', $request->permissions)->get();

            foreach ($permissions as $permission) {
                if ($permission->roles()->count() > 0) {
                    return redirect()->route('permissions.index')->with('error', "'{$permission->name}' ruxsati rollarga biriktirilgan.");
                }
            }

            Permission::whereIn('id', $request->permissions)->delete();

            return redirect()->route('permissions.index')->with('success', count($request->permissions) . ' ta ruxsat o\'chirildi!');
        } catch (\Exception $e) {
            return redirect()->route('permissions.index')->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    private function getApplicationRoutes()
    {
        $routes = [];
        $routeCollection = Route::getRoutes();
        // dd($routeCollection);
        foreach ($routeCollection as $route) {
            $name = $route->getName();

            // Skip unnamed routes and certain patterns
            if (
                !$name ||
                str_starts_with($name, 'ignition.') ||
                str_starts_with($name, 'sanctum.') ||
                str_starts_with($name, '_ignition.') ||
                $name === 'storage.local'
            ) {
                continue;
            }

            $routes[] = [
                'name' => $name,
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
            ];
        }

        // Sort routes alphabetically
        usort($routes, fn($a, $b) => strcmp($a['name'], $b['name']));

        return $routes;
    }

    private function calculateStats($routes)
    {
        $routeNames = collect($routes)->pluck('name')->toArray();
        $permissionNames = Permission::pluck('name')->toArray();

        $synced = count(array_intersect($routeNames, $permissionNames));
        $unsynced = count(array_diff($routeNames, $permissionNames));
        $orphaned = count(array_diff($permissionNames, $routeNames));

        return [
            'total_routes' => count($routes),
            'total_permissions' => count($permissionNames),
            'synced' => $synced,
            'unsynced' => $unsynced,
            'orphaned' => $orphaned,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
