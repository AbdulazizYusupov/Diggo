<?php

namespace App\Http\Controllers\ActivityResult;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityResult;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ActivityResult::with(['activity.group.course', 'student']);

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by activity
        if ($request->filled('activity_id')) {
            $query->where('activity_id', $request->activity_id);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by rank
        if ($request->filled('rank')) {
            $query->where('rank', $request->rank);
        }

        // Filter by earned coins range
        if ($request->filled('coins_from')) {
            $query->where('earned_coins', '>=', $request->coins_from);
        }
        if ($request->filled('coins_to')) {
            $query->where('earned_coins', '<=', $request->coins_to);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $results = $query->paginate(15)->withQueryString();

        // Statistics
        $totalResults = ActivityResult::count();
        $totalCoinsAwarded = ActivityResult::sum('earned_coins');
        $avgCoinsEarned = ActivityResult::avg('earned_coins');
        $topPerformers = ActivityResult::with('student')
            ->select('student_id', DB::raw('SUM(earned_coins) as total_coins'))
            ->groupBy('student_id')
            ->orderByDesc('total_coins')
            ->limit(5)
            ->get();

        $activities = Activity::orderBy('date', 'desc')->get();
        $students = Student::join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('students.*') // Faqat student ustunlarini olish uchun
            ->get();

        return view('ActivityResult.index', compact(
            'results',
            'activities',
            'students',
            'totalResults',
            'totalCoinsAwarded',
            'avgCoinsEarned',
            'topPerformers'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $activities = Activity::with('group.course')
            ->orderBy('date', 'desc')
            ->get();

        $students = Student::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        // If activity_id is provided in query string
        $selectedActivity = null;
        if ($request->filled('activity_id')) {
            $selectedActivity = Activity::find($request->activity_id);
        }

        return view('ActivityResult.create', compact('activities', 'students', 'selectedActivity'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'student_id' => 'required|exists:students,id',
            'earned_coins' => 'required|integer|min:0',
            'rank' => 'nullable|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Check if max_coins is exceeded
            $activity = Activity::findOrFail($validated['activity_id']);
            if ($validated['earned_coins'] > $activity->max_coins) {
                return redirect()->back()
                    ->with('error', "Kiritilgan tangalar soni maksimal tangalar sonidan oshib ketdi! Maksimal: {$activity->max_coins}")
                    ->withInput();
            }

            // Check for duplicate result
            $exists = ActivityResult::where('activity_id', $validated['activity_id'])
                ->where('student_id', $validated['student_id'])
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Bu talaba uchun ushbu faoliyat natijasi allaqachon mavjud!')
                    ->withInput();
            }

            ActivityResult::create($validated);

            DB::commit();

            return redirect()->route('activity-results.index')
                ->with('success', 'Natija muvaffaqiyatli yaratildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = ActivityResult::with(['activity.group.course', 'student'])
            ->findOrFail($id);

        // Get student's other results in the same activity type
        $relatedResults = ActivityResult::with('activity')
            ->where('student_id', $result->student_id)
            ->whereHas('activity', function ($q) use ($result) {
                $q->where('type', $result->activity->type);
            })
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('ActivityResult.show', compact('result', 'relatedResults'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = ActivityResult::findOrFail($id);

        $activities = Activity::with('group.course')
            ->orderBy('date', 'desc')
            ->get();

        $students = Student::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('ActivityResult.edit', compact('result', 'activities', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $result = ActivityResult::findOrFail($id);

        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'student_id' => 'required|exists:students,id',
            'earned_coins' => 'required|integer|min:0',
            'rank' => 'nullable|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Check if max_coins is exceeded
            $activity = Activity::findOrFail($validated['activity_id']);
            if ($validated['earned_coins'] > $activity->max_coins) {
                return redirect()->back()
                    ->with('error', "Kiritilgan tangalar soni maksimal tangalar sonidan oshib ketdi! Maksimal: {$activity->max_coins}")
                    ->withInput();
            }

            // Check for duplicate result (excluding current record)
            $exists = ActivityResult::where('activity_id', $validated['activity_id'])
                ->where('student_id', $validated['student_id'])
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Bu talaba uchun ushbu faoliyat natijasi allaqachon mavjud!')
                    ->withInput();
            }

            $result->update($validated);

            DB::commit();

            return redirect()->route('activity-results.index')
                ->with('success', 'Natija muvaffaqiyatli yangilandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = ActivityResult::findOrFail($id);

        DB::beginTransaction();
        try {
            $result->delete();

            DB::commit();

            return redirect()->route('activity-results.index')
                ->with('success', 'Natija muvaffaqiyatli o\'chirildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Bulk create results for multiple students
     */
    public function bulkCreate(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.earned_coins' => 'required|integer|min:0',
            'results.*.rank' => 'nullable|integer|min:1',
            'results.*.note' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $activity = Activity::findOrFail($validated['activity_id']);

            foreach ($validated['results'] as $resultData) {
                // Validate max_coins
                if ($resultData['earned_coins'] > $activity->max_coins) {
                    throw new \Exception("Talaba ID {$resultData['student_id']} uchun kiritilgan tangalar maksimal qiymatdan oshib ketdi!");
                }

                // Check for duplicates
                $exists = ActivityResult::where('activity_id', $validated['activity_id'])
                    ->where('student_id', $resultData['student_id'])
                    ->exists();

                if (!$exists) {
                    ActivityResult::create([
                        'activity_id' => $validated['activity_id'],
                        'student_id' => $resultData['student_id'],
                        'earned_coins' => $resultData['earned_coins'],
                        'rank' => $resultData['rank'] ?? null,
                        'note' => $resultData['note'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('activity-results.index')
                ->with('success', 'Natijalar muvaffaqiyatli yaratildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }
}