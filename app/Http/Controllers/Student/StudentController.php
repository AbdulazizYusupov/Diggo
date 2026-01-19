<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\Group;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'groups'])
            ->withCount('groups');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        // Balance range filter
        if ($request->filled('balance_range')) {
            switch ($request->balance_range) {
                case 'positive':
                    $query->where('balance', '>', 0);
                    break;
                case 'zero':
                    $query->where('balance', 0);
                    break;
                case 'negative':
                    $query->where('balance', '<', 0);
                    break;
            }
        }

        // Groups count filter
        if ($request->filled('groups_count')) {
            switch ($request->groups_count) {
                case 'none':
                    $query->has('groups', '=', 0);
                    break;
                case 'one':
                    $query->has('groups', '=', 1);
                    break;
                case 'multiple':
                    $query->has('groups', '>', 1);
                    break;
            }
        }

        $students = $query->latest()->paginate(10);

        // Statistics
        $totalStudents = Student::count();
        $activeStudents = Student::whereHas('groups')->count();
        $totalBalance = Student::sum('balance') ?? 0;
        $totalCoins = Student::sum('coins') ?? 0;

        return view('Student.index', compact(
            'students',
            'totalStudents',
            'activeStudents',
            'totalBalance',
            'totalCoins'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        return view('Student.create', compact('schools'));
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

            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',

            'enrollment_date' => 'required|date',
            'balance' => 'nullable|numeric|min:0',
            'coins' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',

            'school_id' => 'nullable|exists:schools,id',
        ]);

        dd($request->all());
        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('teachers-photos', 'public');
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

            $role = Role::where('name', 'teacher')->first();
            if ($role) {
                $user->assignRole($role);
            }

            Student::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'director_id' => $directorId,
                'enrollment_date' => $validated['enrollment_date'],
                'coins' => $validated['coins'] ?? 0,
                'balance' => $validated['balance'] ?? 0,
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'notes' => $validated['notes'],
                'birth_date' => $validated['birth_date'],
                'region' => $validated['region'],
                'city' => $validated['city'],
                'address' => $validated['address'],
            ]);

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'O\'qituvchi muvaffaqiyatli qo\'shildi!');
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
