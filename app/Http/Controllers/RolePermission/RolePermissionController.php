<?php

namespace App\Http\Controllers\RolePermission;

use App\Helpers\PermissionLabel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $query = Role::withCount(['permissions', 'users']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->paginate(12)->withQueryString();

        // Get statistics
        $stats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'roles_with_permissions' => Role::has('permissions')->count(),
            'roles_without_permissions' => Role::doesntHave('permissions')->count(),
        ];

        return view('RolePermission.index', compact('roles', 'search', 'stats'));
    }

    public function show(Role $role)
    {
        $role->load(['permissions' => function ($query) {
            $query->orderBy('name');
        }]);

        $groupedPermissions = $role->permissions
            ->groupBy(fn($permission) => PermissionLabel::parse($permission->name)['module'])
            ->map(fn($permissions, $module) => [
                'module'      => $module,
                'moduleLabel' => PermissionLabel::moduleLabel($module),
                'count'       => $permissions->count(),
                'items'       => $permissions->map(fn($permission) => [
                    'name'  => $permission->name,
                    'label' => PermissionLabel::get($permission->name),
                ]),
            ])
            ->values();

        return view('RolePermission.show', compact('role', 'groupedPermissions'));
    }
    public function edit(Role $role)
    {
        $assignedPermissionIds = $role->permissions->pluck('id')->toArray();

        $groupedPermissions = Permission::orderBy('name')
            ->get()
            ->groupBy(fn($permission) => PermissionLabel::parse($permission->name)['module'])
            ->map(fn($permissions, $module) => [
                'module'         => $module,
                'moduleLabel'    => PermissionLabel::moduleLabel($module),
                'totalCount'     => $permissions->count(),
                'selectedCount'  => $permissions->whereIn('id', $assignedPermissionIds)->count(),
                'items'          => $permissions->map(fn($permission) => [
                    'id'       => $permission->id,
                    'name'     => $permission->name,
                    'label'    => PermissionLabel::get($permission->name),
                    'assigned' => in_array($permission->id, $assignedPermissionIds),
                ]),
            ])
            ->values();

        return view('RolePermission.edit', compact('role', 'groupedPermissions', 'assignedPermissionIds'));
    }
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            // Sync permissions (this will add new ones and remove old ones)
            $permissions = $request->input('permissions', []);
            $role->syncPermissions(Permission::whereIn('id', $permissions)->get());

            return redirect()
                ->route('role-permissions.index')
                ->with('success', "'{$role->name}' roli uchun ruxsatlar muvaffaqiyatli yangilandi! Jami " . count($permissions) . " ta ruxsat biriktirildi.");
        } catch (\Exception $e) {
            return redirect()
                ->route('role-permissions.edit', $role)
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $role = Role::findOrFail($request->role_id);
            $permissions = Permission::whereIn('id', $request->permissions)->get();

            // Give permissions to role (without removing existing ones)
            $role->givePermissionTo($permissions);

            return redirect()
                ->route('role-permissions.index')
                ->with('success', count($request->permissions) . " ta ruxsat '{$role->name}' roliga qo'shildi!");
        } catch (\Exception $e) {
            return redirect()
                ->route('role-permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    public function copyPermissions(Request $request)
    {
        $request->validate([
            'from_role_id' => 'required|exists:roles,id',
            'to_role_id' => 'required|exists:roles,id|different:from_role_id'
        ]);

        try {
            $fromRole = Role::findOrFail($request->from_role_id);
            $toRole = Role::findOrFail($request->to_role_id);

            $permissions = $fromRole->permissions;
            $toRole->syncPermissions($permissions);

            return redirect()
                ->route('role-permissions.index')
                ->with('success', "'{$fromRole->name}' rolidan '{$toRole->name}' roliga " . $permissions->count() . " ta ruxsat ko'chirildi!");
        } catch (\Exception $e) {
            return redirect()
                ->route('role-permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    public function revokeAll(Role $role)
    {
        try {
            $count = $role->permissions()->count();
            $role->syncPermissions([]);

            return redirect()
                ->route('role-permissions.index')
                ->with('success', "'{$role->name}' rolidan {$count} ta ruxsat olib tashlandi!");
        } catch (\Exception $e) {
            return redirect()
                ->route('role-permissions.index')
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}
