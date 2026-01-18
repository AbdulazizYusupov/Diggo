<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'groups'])
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

        // Salary type filter
        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Salary range filter
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

        $teachers = $query->latest()->paginate(15);

        // Statistics
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::where('is_active', true)->count();
        $avgSalary = Teacher::avg('salary_amount') ?? 0;
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
        $teacher = Teacher::findOrFail($id);
        return view('Teacher.edit', compact('teacher'));
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
