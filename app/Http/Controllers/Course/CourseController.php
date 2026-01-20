<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\School;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query
        $query = Cours::query()->with(['school']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // School filter
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Level filter
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

        return view('Course.index', compact(
            'courses',
            'totalCourses',
            'activeCourses',
            'totalRevenue',
            'avgPrice',
        ));
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
