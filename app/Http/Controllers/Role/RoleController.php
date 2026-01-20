<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $roles = Role::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->withCount('users')
            ->latest()
            ->paginate(12)
            ->withQueryString();
        return view('Role.index', compact('roles', 'search'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.required' => 'Rol nomi kiritilishi shart',
            'name.unique' => 'Bu rol nomi allaqachon mavjud',
            'name.max' => 'Rol nomi 255 belgidan oshmasligi kerak',
        ]);

        Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('roles.index')
            ->with('success', 'Rol muvaffaqiyatli yaratildi');
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
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ], [
            'name.required' => 'Rol nomi kiritilishi shart',
            'name.unique' => 'Bu rol nomi allaqachon mavjud',
            'name.max' => 'Rol nomi 255 belgidan oshmasligi kerak',
        ]);

        $role->update(['name' => $validated['name']]);

        return redirect()->route('roles.index')
            ->with('success', 'Rol muvaffaqiyatli yangilandi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Bu rol foydalanuvchilarga biriktirilgan, avval foydalanuvchilardan olib tashlang');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol muvaffaqiyatli o\'chirildi');
    }
}
