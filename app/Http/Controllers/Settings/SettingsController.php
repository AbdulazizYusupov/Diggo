<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $roleFilter = $request->get('role', '');
        $typeFilter = $request->get('type', '');

        // Foydalanuvchilarni olish
        $users = User::with(['roles', 'student', 'teacher', 'staff', 'director'])
            ->when(Auth::id() !== 1, function ($query) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                });
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $roleFilter) {
                $query->role($roleFilter);
            })
            ->when($typeFilter, function ($query, $typeFilter) {
                switch ($typeFilter) {
                    case 'teacher':
                        $query->whereHas('teacher');
                        break;
                    case 'student':
                        $query->whereHas('student');
                        break;
                    case 'staff':
                        $query->whereHas('staff');
                        break;
                    case 'director':
                        $query->whereHas('director');
                        break;
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Barcha rollar
        $roles = Role::withCount('users')->get();

        // Statistika
        $stats = [
            'total_users' => User::count(),
            'total_teachers' => User::whereHas('teacher')->count(),
            'total_students' => User::whereHas('student')->count(),
            'total_staff' => User::whereHas('staff')->count(),
            'total_directors' => User::whereHas('director')->count(),
            'users_with_roles' => User::has('roles')->count(),
            'users_without_roles' => User::doesntHave('roles')->count(),
            'total_roles' => $roles->count(),
        ];

        return view('Settings.index', compact('users', 'roles', 'stats', 'search', 'roleFilter', 'typeFilter'));
    }

    /**
     * Foydalanuvchiga rol biriktirish
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->assignRole($request->role_name);

        return redirect()->route('settings.index')
            ->with('success', "{$user->full_name} ga {$request->role_name} roli biriktirildi!");
    }

    /**
     * Foydalanuvchidan rolni olib tashlash
     */
    public function removeRole(Request $request, User $user)
    {
        if ($request->role_name == 'admin') {
            return redirect()->back()->with('error', 'Admin rolini olib tashlash butun tizimni ishdan chiqaradi');
        }
        $request->validate([
            'role_name' => 'required|exists:roles,name',
        ]);

        $user->removeRole($request->role_name);

        return redirect()->route('settings.index')
            ->with('success', "{$user->full_name} dan {$request->role_name} roli olib tashlandi!");
    }

    /**
     * Foydalanuvchi rollarini yangilash (sync)
     */
    public function syncRoles(Request $request, User $user)
    {
        if ($user->id == 1) {
            return redirect()->back()->with('error', 'Superadmin ruxsatlarini o\'zgartirish taqiqlangan!');
        }

        if ($user->hasRole('admin') && Auth::id() != 1) {
            return redirect()->back()->with('error', 'Siz boshqa adminlarning ruxsatlarini o\'zgartira olmaysiz!');
        }


        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $roles = $request->input('roles', []);

        $user->syncRoles($roles);

        if (in_array('admin', $roles) && Auth::id() != 1) {
            return redirect()->back()->with('error', 'Admin roli berish huquqi faqat Superadmindagina bor!');
        }

        $message = empty($roles)
            ? "{$user->full_name} dan barcha rollar olib tashlandi!"
            : "{$user->full_name} rollari yangilandi!";

        return redirect()->route('settings.index')->with('success', $message);
    }
}
