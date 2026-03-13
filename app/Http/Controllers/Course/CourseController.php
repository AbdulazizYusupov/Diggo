<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\School;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private array $levelLabels = [
        'beginner'     => "Boshlang'ich",
        'intermediate' => "O'rta",
        'advanced'     => 'Yuqori',
    ];

    private array $levelColors = [
        'beginner'     => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        'intermediate' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        'advanced'     => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query
        $query = Cours::query()->with('groups');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Price range filter
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'low':
                    $query->where('price', '<', 500000);
                    break;
                case 'medium':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case 'high':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // Duration filter
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case 'short':
                    $query->where('duration_months', '<=', 3);
                    break;
                case 'medium':
                    $query->whereBetween('duration_months', [4, 6]);
                    break;
                case 'long':
                    $query->where('duration_months', '>', 6);
                    break;
            }
        }

        // Active status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'active');
        }

        // Get courses with pagination
        $courses = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalCourses = Cours::count();
        $activeCourses = Cours::where('is_active', true)->count();
        $totalRevenue = Cours::where('is_active', true)->sum('price');
        $avgPrice = Cours::where('is_active', true)->avg('price');
        $levelLabels   = $this->levelLabels;
        $levelColors   = $this->levelColors;
        return view('Course.index', compact(
            'courses',
            'totalCourses',
            'activeCourses',
            'totalRevenue',
            'avgPrice',
            'levelLabels',
            'levelColors'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Course.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cours,code',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:36',
            'level' => 'required|in:beginner,intermediate,advanced',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Cours::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Kurs muvaffaqiyatli qo\'shildi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Cours::with(['groups'])
            ->withCount('groups')
            ->findOrFail($id);

        // Statistika ma'lumotlari
        $stats = [
            'total_groups' => $course->groups()->count(),
            'active_groups' => $course->groups()->where('is_active', true)->count(),
            'total_students' => $course->groups()->withCount('students')->get()->sum('students_count'),
            'potential_revenue' => $course->groups()->withCount('students')->get()->sum('students_count') * $course->price,
        ];

        // So'nggi 5 ta guruh
        $recentGroups = $course->groups()
            ->withCount('students')
            ->latest()
            ->limit(5)
            ->get();

        return view('Course.show', compact('course', 'stats', 'recentGroups'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Cours::findOrFail($id);
        return view('Course.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Cours::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cours,code,' . $course->id,
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:36',
            'level' => 'required|in:beginner,intermediate,advanced',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $course->update($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Kurs ma\'lumotlari muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Cours::findOrFail($id);
        if ($course->groups()->count() > 0) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Bu kursda ' . $course->groups()->count() . ' ta guruh mavjud. Avval guruhlarni o\'chiring!');
        }
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Kurs muvaffaqiyatli o\'chirildi!');
    }
}
