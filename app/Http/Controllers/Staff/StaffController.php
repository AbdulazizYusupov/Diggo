<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Staff::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by salary type
        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $staffs = $query->latest()->paginate(10);

        // Statistics
        $activeCount = Staff::where('is_active', true)->count();
        $inactiveCount = Staff::where('is_active', false)->count();
        $avgSalary = Staff::where('salary_type', 'fixed')->avg('salary_amount') ?? 0;

        return view('Staff.index', compact('staffs', 'activeCount', 'inactiveCount', 'avgSalary'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|min:8|unique:users,phone',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'salary_type' => 'required|in:fixed,percent,per_student',
            'salary_amount' => 'required_if:salary_type,fixed,per_student|nullable|numeric|min:0',
            'percent_per_student' => 'required_if:salary_type,percent|nullable|numeric|min:0|max:100',
            'percent_per_payment' => 'required_if:salary_type,percent|nullable|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('staff-photos', 'public');
            }

            $user = User::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'middle_name' => $validated['middle_name'] ?? null,
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'],
                'city' => $validated['city'] ?? null,
                'region' => $validated['region'] ?? null,
                'address' => $validated['address'] ?? null,
                'password' => Hash::make($validated['password']),
                'photo' => $photoPath,
                'is_active' => $request->has('is_active'),
            ]);

            $roleName = 'staff';
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $user->assignRole($role);
            }

            Staff::create([
                'user_id' => $user->id,
                'salary_type' => $validated['salary_type'],
                'salary_amount' => $validated['salary_type'] !== 'percent' ? ($validated['salary_amount'] ?? 0) : 0,
                'percent_per_student' => $validated['salary_type'] === 'percent' ? ($validated['percent_per_student'] ?? 0) : 0,
                'percent_per_payment' => $validated['salary_type'] === 'percent' ? ($validated['percent_per_payment'] ?? 0) : 0,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return redirect()->route('staffs.index')->with('success', 'Yangi xodim muvaffaqiyatli qo\'shildi!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            return back()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::findOrFail($id);
        return view('Staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail($id);
        return view('Staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $staff = Staff::findOrFail($id);
        // 1. Validatsiya
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|min:8|unique:users,phone,' . $staff->user_id,
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'salary_type' => 'required|in:fixed,percent,per_student',
            'salary_amount' => 'required_if:salary_type,fixed,per_student|nullable|numeric|min:0',
            'percent_per_student' => 'required_if:salary_type,percent|nullable|numeric|min:0|max:100',
            'percent_per_payment' => 'required_if:salary_type,percent|nullable|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $user = $staff->user;

            if ($request->hasFile('photo')) {
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $validated['photo'] = $request->file('photo')->store('staff-photos', 'public');
            } else {
                $validated['photo'] = $user->photo;
            }

            $userData = [
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'middle_name' => $validated['middle_name'],
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'city' => $validated['city'],
                'region' => $validated['region'],
                'address' => $validated['address'],
                'photo' => $validated['photo'],
                'is_active' => $request->has('is_active'),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);
            $staff->update([
                'salary_type' => $validated['salary_type'],
                'salary_amount' => $validated['salary_type'] !== 'percent' ? ($validated['salary_amount'] ?? 0) : 0,
                'percent_per_student' => $validated['salary_type'] === 'percent' ? ($validated['percent_per_student'] ?? 0) : 0,
                'percent_per_payment' => $validated['salary_type'] === 'percent' ? ($validated['percent_per_payment'] ?? 0) : 0,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return redirect()->route('staffs.index')->with('success', 'Xodim ma’lumotlari muvaffaqiyatli yangilandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Xatolik: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        $staff = Staff::findOrFail($id);
        $user = $staff->user;

        if ($user && $user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $staff->delete();

        return redirect()->route('staffs.index')->with('success', 'Xodim muvaffaqiyatli o\'chirildi');
    }
}
