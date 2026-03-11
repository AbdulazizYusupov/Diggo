<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'groups'])
            ->withCount('groups');

        // 1. Search filter (Qidiruv)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        // Familiya Ism Otasining ismi
                        ->orWhereRaw("CONCAT(surname, ' ', name, ' ', COALESCE(middle_name, '')) LIKE ?", ["%{$search}%"])
                        // Familiya Otasining ismi
                        ->orWhereRaw("CONCAT(surname, ' ', COALESCE(middle_name, '')) LIKE ?", ["%{$search}%"])
                        // Ism Otasining ismi
                        ->orWhereRaw("CONCAT(name, ' ', COALESCE(middle_name, '')) LIKE ?", ["%{$search}%"]);
                });
            });
        }

        // 2. Salary type filter
        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        // 3. Status filter - User modelidagi is_active bo'yicha
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->whereHas('user', function ($q) use ($isActive) {
                $q->where('is_active', $isActive);
            });
        }

        // 4. Salary range filter
        if ($request->filled('salary_range')) {
            switch ($request->salary_range) {
                case 'low':
                    $query->where('salary_amount', '<', 2000000);
                    break;
                case 'medium':
                    $query->whereBetween('salary_amount', [2000000, 5000000]);
                    break;
                case 'high':
                    $query->where('salary_amount', '>', 5000000);
                    break;
            }
        }

        $teachers = $query->latest()->paginate(10);

        // 5. Statistics (Statistikani ham User holatiga moslaymiz)
        $totalTeachers = Teacher::count();

        $activeTeachers = Teacher::whereHas('user', function ($q) {
            $q->where('is_active', true);
        })->count();

        $avgSalary = Teacher::avg('salary_amount') ?? 0;

        // Guruhlar sonini hisoblash (Teacher modelidagi munosabatga qarab)
        $totalGroups = DB::table('group_teachers')->distinct('group_id')->count();

        return view('Teacher.index', compact(
            'teachers',
            'totalTeachers',
            'activeTeachers',
            'avgSalary',
            'totalGroups'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Teacher.create');
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

            'salary_type' => 'required|in:fixed,percent,hourly,per_group',
            'salary_amount' => 'required_if:salary_type,fixed,hourly,per_group|nullable|numeric|min:0',
            'percent_per_student' => 'required_if:salary_type,percent|nullable|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

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

            Teacher::create([
                'user_id' => $user->id,
                'salary_type' => $validated['salary_type'],
                'salary_amount' => $validated['salary_type'] !== 'percent' ? ($validated['salary_amount'] ?? 0) : 0,
                'percent_per_student' => $validated['salary_type'] === 'percent' ? ($validated['percent_per_student'] ?? 0) : 0,
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
        $teacher = Teacher::findOrFail($id);

        return view('Teacher.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('Teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user; // O'qituvchiga bog'langan User modeli

        // 1. Validatsiya
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            // Telefon raqami o'zinikidan boshqa foydalanuvchilarda takrorlanmasligi kerak
            'phone' => 'required|string|max:20|min:8|unique:users,phone,' . $user->id,
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6', // Parol ixtiyoriy
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'salary_type' => 'required|in:fixed,percent,hourly,per_group',
            'salary_amount' => 'required_if:salary_type,fixed,hourly,per_group|nullable|numeric|min:0',
            'percent_per_student' => 'required_if:salary_type,percent|nullable|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // 2. Rasmni yangilash
            if ($request->hasFile('photo')) {
                // Eski rasmni o'chirish
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $photoPath = $request->file('photo')->store('teachers-photos', 'public');
                $user->photo = $photoPath;
            }

            // 3. Foydalanuvchi ma'lumotlarini yangilash
            $user->name = $validated['name'];
            $user->surname = $validated['surname'];
            $user->middle_name = $validated['middle_name'] ?? null;
            $user->phone = $validated['phone'];
            $user->birth_date = $validated['birth_date'] ?? null;
            $user->gender = $validated['gender'];
            $user->city = $validated['city'] ?? null;
            $user->region = $validated['region'] ?? null;
            $user->address = $validated['address'] ?? null;
            $user->is_active = $request->has('is_active');

            // Agar parol kiritilgan bo'lsa, uni yangilaymiz
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            // 4. Teacher (O'qituvchi) jadvalidagi ma'lumotlarni yangilash
            $teacher->update([
                'salary_type' => $validated['salary_type'],
                'salary_amount' => $validated['salary_type'] !== 'percent' ? ($validated['salary_amount'] ?? 0) : 0,
                'percent_per_student' => $validated['salary_type'] === 'percent' ? ($validated['percent_per_student'] ?? 0) : 0,
            ]);

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'O\'qituvchi ma\'lumotlari yangilandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        DB::beginTransaction();
        try {
            $user = $teacher->user;
            $photoPath = $user->photo;

            // Delete teacher record
            $teacher->delete();

            // Delete user record
            $user->delete();

            // Delete photo if exists
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'O\'qituvchi o\'chirildi!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}
