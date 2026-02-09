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
            ->where('users.is_active', true)
            ->orderBy('users.name', 'asc')
            ->select('students.*') // Faqat student obyektlarini olish uchun
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:students,id',
            'earned_coins' => 'required|integer|min:0',
            'start_rank' => 'nullable|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $activity = Activity::findOrFail($validated['activity_id']);

            // Maksimal tangalar sonini tekshirish
            if ($validated['earned_coins'] > $activity->max_coins) {
                return redirect()->back()
                    ->with('error', "Kiritilgan tangalar soni maksimal tangalar sonidan oshib ketdi! Maksimal: {$activity->max_coins}")
                    ->withInput();
            }

            $createdCount = 0;
            $skippedCount = 0;
            $currentRank = $validated['start_rank'] ?? null;

            foreach ($validated['student_ids'] as $studentId) {
                // Avval ushbu talaba bu faoliyatda qatnashganini tekshirish
                $exists = ActivityResult::where('activity_id', $validated['activity_id'])
                    ->where('student_id', $studentId)
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                // 1. Natijani saqlash
                ActivityResult::create([
                    'activity_id' => $validated['activity_id'],
                    'student_id' => $studentId,
                    'earned_coins' => $validated['earned_coins'],
                    'rank' => $currentRank,
                    'note' => $validated['note'],
                ]);

                // 2. Talabaning umumiy balansiga tangalarni qo'shish
                // increment() - bazada to'g'ridan-to'g'ri qiymatni oshiradi
                Student::where('id', $studentId)->increment('coins', $validated['earned_coins']);

                $createdCount++;

                // Agar rank (o'rin) berilgan bo'lsa, uni oshirib borish
                if ($currentRank !== null) {
                    $currentRank++;
                }
            }

            DB::commit();

            $message = "{$createdCount} ta natija muvaffaqiyatli yaratildi va talabalar hisobiga tangalar qo'shildi!";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} ta takroriy natija o'tkazib yuborildi)";
            }

            return redirect()->route('activityresults.index')
                ->with('success', $message);

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

        $students = Student::join('users', 'students.user_id', '=', 'users.id')
            ->where('users.is_active', true)
            ->orderBy('users.name', 'asc')
            ->select('students.*') // Faqat student obyektlarini olish uchun
            ->get();

        return view('ActivityResult.edit', compact('result', 'activities', 'students'));
    }

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
            $activity = Activity::findOrFail($validated['activity_id']);
            if ($validated['earned_coins'] > $activity->max_coins) {
                return redirect()->back()
                    ->with('error', "Kiritilgan tangalar soni maksimal tangalar sonidan oshib ketdi! Maksimal: {$activity->max_coins}")
                    ->withInput();
            }

            $exists = ActivityResult::where('activity_id', $validated['activity_id'])
                ->where('student_id', $validated['student_id'])
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Bu talaba uchun ushbu faoliyat natijasi allaqachon mavjud!')
                    ->withInput();
            }

            // --- TANGALARNI YANGILASH LOGIKASI ---

            // 1. Avvalgi talabani topamiz (student_id o'zgargan bo'lishi ham mumkin)
            $oldStudent = Student::findOrFail($result->student_id);
            // 2. Eski tangani uning balansidan ayiramiz
            $oldStudent->decrement('coins', $result->earned_coins);

            // 3. Natijani bazada yangilaymiz
            $result->update($validated);

            // 4. Yangi talabani topamiz (agar student_id o'zgargan bo'lsa yangisini, o'zgarmagan bo'lsa o'shani)
            $newStudent = Student::findOrFail($validated['student_id']);
            // 5. Yangi tangani qo'shamiz
            $newStudent->increment('coins', $validated['earned_coins']);

            DB::commit();

            return redirect()->route('activityresults.index')
                ->with('success', 'Natija muvaffaqiyatli yangilandi va talaba balansi qayta hisoblandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy(string $id)
    {
        $result = ActivityResult::findOrFail($id);

        DB::beginTransaction();
        try {
            $result->delete();

            DB::commit();

            return redirect()->route('activityresults.index')
                ->with('success', 'Natija muvaffaqiyatli o\'chirildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}