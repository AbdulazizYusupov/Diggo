<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Group::with(['course', 'teachers', 'activeStudents']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Course filter
        if ($request->filled('course_id')) {
            $query->where('cours_id', $request->course_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Capacity filter
        if ($request->filled('capacity_range')) {
            switch ($request->capacity_range) {
                case 'small':
                    $query->where('capacity', '<', 15);
                    break;
                case 'medium':
                    $query->whereBetween('capacity', [15, 25]);
                    break;
                case 'large':
                    $query->where('capacity', '>', 25);
                    break;
            }
        }

        // Days filter
        if ($request->filled('days')) {
            $query->whereJsonContains('days_of_week', $request->days);
        }

        $groups = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalGroups = Group::count();
        $activeGroups = Group::where('is_active', true)->count();
        $totalStudents = DB::table('group_students')
            ->where('status', 'active')
            ->distinct('student_id')
            ->count();
        $avgCapacity = Group::avg('capacity');

        // Courses for filter
        $courses = Cours::where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'code']);

        return view('Group.index', compact(
            'groups',
            'totalGroups',
            'activeGroups',
            'totalStudents',
            'avgCapacity',
            'courses'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $courses = Cours::where('is_active', true)->get();
        $preselectedCourseId = $request->input('course_id');
        return view('Group.create', compact('courses', 'preselectedCourseId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cours_id' => 'required|exists:cours,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'capacity' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $group = Group::create($validated);

        return redirect()
            ->route('groups.index', $group)
            ->with('success', 'Guruh muvaffaqiyatli yaratildi!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = Group::findOrFail($id);
        $group->load(['course', 'teachers', 'activeStudents']);

        $stats = [
            'total_students' => $group->activeStudents()->count(),
            'total_teachers' => $group->teachers()->count(),
            'capacity_used' => $group->capacity > 0
                ? round(($group->activeStudents()->count() / $group->capacity) * 100)
                : 0,
            'days_count' => count($group->days_of_week ?? [])
        ];

        return view('Group.show', compact('group', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $group = Group::findOrFail($id);
        $courses = Cours::where('is_active', true)->get();
        return view('Group.edit', compact('group', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $group = Group::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cours_id' => 'required|exists:cours,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'capacity' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $group->update($validated);

        return redirect()
            ->route('groups.index', $group)
            ->with('success', 'Guruh muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect()
            ->route('groups.index')
            ->with('success', 'Guruh muvaffaqiyatli o\'chirildi!');
    }
}
