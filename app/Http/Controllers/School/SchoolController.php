<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query
        $query = School::query()->with(['director.user']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Region filter
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Active status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'active');
        }

        // Director filter
        if ($request->filled('has_director')) {
            if ($request->has_director === 'yes') {
                $query->has('director');
            } elseif ($request->has_director === 'no') {
                $query->doesntHave('director');
            }
        }

        // Get schools with pagination
        $schools = $query->latest()->paginate(10)->withQueryString();

        // Statistics
        $totalSchools = School::count();
        $activeSchools = School::where('is_active', true)->count();
        $schoolsWithDirector = School::has('director')->count();
        $totalStudents = DB::table('students')->whereNull('deleted_at')->count();

        // Get unique regions and cities for filters
        $regions = School::select('region')
            ->distinct()
            ->whereNotNull('region')
            ->pluck('region')
            ->sort();

        $cities = School::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->pluck('city')
            ->sort();

        return view('School.index', compact(
            'schools',
            'totalSchools',
            'activeSchools',
            'schoolsWithDirector',
            'totalStudents',
            'regions',
            'cities'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('School.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code',
            'phone' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        // is_active default true
        $validated['is_active'] = $request->has('is_active') ? true : false;

        School::create($validated);

        return redirect()
            ->route('schools.index')
            ->with('success', 'Maktab muvaffaqiyatli qo\'shildi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $school = School::with(['director.user'])
        ->withCount('students')
        ->findOrFail($id);

    // Statistika ma'lumotlari
   $stats = [
    'total_students'  => $school->students_count,
    
    'active_students' => $school->students()->whereHas('user', fn($q) => 
        $q->where('is_active', true)
    )->count(),
    
    'male_students'   => $school->students()->whereHas('user', fn($q) => 
        $q->where('gender', 'male')
    )->count(),
    
    'female_students' => $school->students()->whereHas('user', fn($q) => 
        $q->where('gender', 'female')
    )->count(),
]; // Mana bu yerda kvadrat qavs bo'lishi shart!
    // Students with filtering and pagination
    $studentsQuery = $school->students()->with('user');

    // Search filter
    if (request()->filled('search')) {
        $search = request('search');
        $studentsQuery->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('surname', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // Status filter
    if (request()->filled('status')) {
        $status = request('status') === 'active';
        $studentsQuery->whereHas('user', function ($q) use ($status) {
            $q->where('is_active', $status);
        });
    }

    // Gender filter
    if (request()->filled('gender')) {
        $studentsQuery->whereHas('user', function ($q) {
            $q->where('gender', request('gender'));
        });
    }

    // Sorting
    $sortBy = request('sort_by', 'latest');
    switch ($sortBy) {
        case 'name':
            $studentsQuery->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', 'asc')
                ->select('students.*');
            break;
        case 'oldest':
            $studentsQuery->oldest();
            break;
        default: // latest
            $studentsQuery->latest();
            break;
    }

    $students = $studentsQuery->paginate(12)->appends(request()->query());

    return view('School.show', compact('school', 'stats', 'students'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $school = School::findOrFail($id);
        return view('School.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $school = School::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
            'phone' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        // is_active checkbox handling
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $school->update($validated);

        return redirect()
            ->route('schools.index')
            ->with('success', 'Maktab ma\'lumotlari muvaffaqiyatli yangilandi!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $school = School::findOrFail($id);

            // Maktabda talabalar borligini tekshirish
            if ($school->students()->count() > 0) {
                return redirect()
                    ->route('schools.index')
                    ->with('error', 'Bu maktabda talabalar mavjud. Avval talabalarni boshqa maktabga o\'tkazing yoki o\'chiring.');
            }

            // Direktorni o'chirish (agar mavjud bo'lsa)
            if ($school->director) {
                $school->director->delete();
            }

            $schoolName = $school->name;
            $school->delete();

            return redirect()
                ->route('schools.index')
                ->with('success', "{$schoolName} maktabi muvaffaqiyatli o'chirildi.");
        } catch (\Exception $e) {
            return redirect()
                ->route('schools.index')
                ->with('error', 'Maktabni o\'chirishda xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}
