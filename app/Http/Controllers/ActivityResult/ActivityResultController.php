<?php

namespace App\Http\Controllers\ActivityResult;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityResult;
use App\Models\CoinTransaction;
use App\Models\Group;
use App\Models\Student;
use App\Notifications\ActivityResult\ActivityResultNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $groups = Group::whereHas('students')
            ->orderBy('id')
            ->get();
        return view('ActivityResult.index', compact(
            'results',
            'activities',
            'students',
            'totalResults',
            'totalCoinsAwarded',
            'avgCoinsEarned',
            'topPerformers',
            'groups'
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

    public function store1(Request $request)
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
                dd($activity);
                $student = Student::findOrFail($studentId);
                $student->notify(new ActivityResultNotification(
                    activityName: $activity->name,
                    activityType: $activity->type,
                    earnedCoins: $validated['earned_coins'],
                    maxCoins: $activity->max_coins,
                    rank: $currentRank,         // before incrementing
                    activityDate: $activity->date->format('d.m.Y'),
                    groupName: $activity->group->name,
                    note: $validated['note'] ?? null,
                ));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id'   => 'required|integer|exists:activities,id',
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'required|integer|exists:students,id',
            'earned_coins'  => 'required|integer|min:0',
            'start_rank'    => 'nullable|integer|min:1',
            'note'          => 'nullable|string|max:1000',
        ]);

        $requestedStudentIds = array_unique($validated['student_ids']);

        DB::beginTransaction();

        try {
            /** @var Activity $activity */
            $activity = Activity::with('group')->lockForUpdate()->findOrFail($validated['activity_id']);

            // ── 1. Tangalar chegarasini tekshirish ──
            if ($validated['earned_coins'] > $activity->max_coins) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', "Kiritilgan tangalar maksimal chegaradan oshib ketdi! Maksimal: {$activity->max_coins} tanga.")
                    ->withInput();
            }

            // ── 2. Allaqachon natija kiritilgan talabalarni aniqlash (bitta so'rov) ──
            $alreadyRecordedSet = array_flip(
                ActivityResult::where('activity_id', $activity->id)
                    ->whereIn('student_id', $requestedStudentIds)
                    ->pluck('student_id')
                    ->all()
            );

            // ── 3. Yangi va takroriy talabalarni ajratish ──
            $newStudentIds     = [];
            $skippedStudentIds = [];

            foreach ($requestedStudentIds as $studentId) {
                if (isset($alreadyRecordedSet[$studentId])) {
                    $skippedStudentIds[] = $studentId;
                } else {
                    $newStudentIds[] = $studentId;
                }
            }

            // ── 4. Barchasi takroriy bo'lsa ──
            if (empty($newStudentIds)) {
                DB::rollBack();

                $count = count($skippedStudentIds);
                return redirect()->back()
                    ->with('warning', "Barcha {$count} ta talaba bu faoliyatda allaqachon qatnashgan.")
                    ->withInput();
            }

            // ── 5. ActivityResult — bulk insert ──
            $now         = now();
            $currentRank = isset($validated['start_rank']) ? (int) $validated['start_rank'] : null;
            $resultRows  = [];
            // Notification uchun rank snapshot saqlash (insert dan keyin rank o'zgaradi)
            $rankSnapshot = [];

            foreach ($newStudentIds as $studentId) {
                $resultRows[] = [
                    'activity_id'  => $activity->id,
                    'student_id'   => $studentId,
                    'earned_coins' => $validated['earned_coins'],
                    'rank'         => $currentRank,
                    'note'         => $validated['note'] ?? null,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];

                // Har bir talaba uchun rank ni eslab qolish
                $rankSnapshot[$studentId] = $currentRank;

                if ($currentRank !== null) {
                    $currentRank++;
                }
            }

            ActivityResult::insert($resultRows);

            // ── 6. Talabalar balansini yangilash va yangilangan balanslarni olish ──
            // Avval increment, so'ng yangilangan qiymatlarni bitta so'rovda yuklash
            Student::whereIn('id', $newStudentIds)
                ->increment('coins', $validated['earned_coins']);

            $students = Student::whereIn('id', $newStudentIds)
                ->with('user')
                ->get(['id', 'user_id', 'coins'])
                ->keyBy('id');

            // ── 7. CoinTransaction — bulk insert ──
            $transactionType = $this->resolveTransactionType($activity->type);
            $transactionRows = [];

            foreach ($newStudentIds as $studentId) {
                $balanceAfter = $students[$studentId]->coins ?? 0;

                $transactionRows[] = [
                    'student_id'       => $studentId,
                    'user_id'          => Auth::id(),
                    'activity_id'      => $activity->id,
                    'coins'            => $validated['earned_coins'],
                    'type'             => $transactionType,
                    'description'      => "'{$activity->name}' faoliyati uchun tanga berildi.",
                    'balance_after'    => $balanceAfter,
                    'transaction_date' => $now->toDateString(),
                    'expiry_date'      => null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }

            CoinTransaction::insert($transactionRows);

            DB::commit();

            // ── 8. Notifications — DB commit dan KEYIN yuboriladi ──
            // Queue ishlatilgani uchun transaction ichida yuborish shart emas.
            // Bu yerda Student modellari allaqachon yuklangan, N+1 yo'q.
            $activityDate = $activity->date?->format('d.m.Y') ?? $now->format('d.m.Y');
            $groupName    = $activity->group?->name ?? '';

            foreach ($students as $studentId => $student) {
                if (! $student->user) {
                    continue;
                }
                $student->user->notify(new ActivityResultNotification(
                    activityName: $activity->name,
                    activityType: $activity->type,
                    earnedCoins: $validated['earned_coins'],
                    maxCoins: $activity->max_coins,
                    rank: $rankSnapshot[$studentId] ?? null,
                    activityDate: $activityDate,
                    groupName: $groupName,
                    note: $validated['note'] ?? null,
                ));
            }

            // ── 9. Muvaffaqiyat xabari ──
            $createdCount = count($newStudentIds);
            $skippedCount = count($skippedStudentIds);

            $message = "{$createdCount} ta natija muvaffaqiyatli saqlandi va talabalar hisobiga tangalar qo'shildi!";

            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} ta takroriy natija o'tkazib yuborildi)";
            }

            return redirect()->route('activityresults.index')->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect()->back()
                ->with('error', "Xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring.")
                ->withInput();
        }
    }

    /**
     * Activity turini CoinTransaction enum turiga moslashtirish.
     * coin_transactions.type: participation | homework | exam |
     *   competition | active_involvement | penalty | bonus | purchase | refund | other
     */
    private function resolveTransactionType(string $activityType): string
    {
        return match ($activityType) {
            'homework'           => 'homework',
            'exam', 'test'       => 'exam',
            'competition'        => 'competition',
            'active_involvement',
            'activity'           => 'active_involvement',
            'participation'      => 'participation',
            'bonus'              => 'bonus',
            'penalty'            => 'penalty',
            default              => 'other',
        };
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
