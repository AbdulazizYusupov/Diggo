<?php

namespace App\Services\Dashboard;

use App\Models\Group;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    // ─────────────────────────────────────────────────────────────────────────
    //  KPIs
    // ─────────────────────────────────────────────────────────────────────────

    public function getTotalStudents(): int
    {
        return Student::count();
    }

    public function getActiveGroupsCount(): int
    {
        return Group::where('is_active', true)->count();
    }

    public function getTotalTeachers(): int
    {
        return Teacher::count();
    }

    public function getNewStudentsThisMonth(): int
    {
        return Student::whereMonth('enrollment_date', now()->month)
            ->whereYear('enrollment_date', now()->year)
            ->count();
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  REVENUE
    // ─────────────────────────────────────────────────────────────────────────

    public function getMonthlyRevenue(): float|int
    {
        return Payment::paid()->thisMonth()->sum('net_amount');
    }

    public function getPrevMonthRevenue(): float|int
    {
        $prev = now()->subMonth();

        return Payment::paid()
            ->whereMonth('paid_at', $prev->month)
            ->whereYear('paid_at', $prev->year)
            ->sum('net_amount');
    }

    public function getRevenueGrowth(float|int $monthly, float|int $prevMonthRevenue): ?float
    {
        if ($prevMonthRevenue <= 0) {
            return null;
        }

        return round(($monthly - $prevMonthRevenue) / $prevMonthRevenue * 100, 1);
    }

    public function getPendingPaymentsCount(): int
    {
        return Payment::pending()->count();
    }

    public function getPendingPaymentsAmount(): float|int
    {
        return Payment::pending()->sum('net_amount');
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  ATTENDANCE
    // ─────────────────────────────────────────────────────────────────────────

    public function getMonthlyAttendanceStats(): array
    {
        $row = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->whereMonth('lessons.lesson_date', now()->month)
            ->whereYear('lessons.lesson_date', now()->year)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as excused
            ', ['present', 'late', 'absent', 'excused'])
            ->first();

        $total   = (int) ($row->total   ?? 0);
        $present = (int) ($row->present ?? 0);
        $late    = (int) ($row->late    ?? 0);

        return [
            'total'   => $total,
            'present' => $present,
            'late'    => $late,
            'absent'  => (int) ($row->absent  ?? 0),
            'excused' => (int) ($row->excused ?? 0),
            'rate'    => $total > 0
                ? round(($present + $late) / $total * 100, 1)
                : 0.0,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  TODAY'S LESSONS
    // ─────────────────────────────────────────────────────────────────────────

    public function getTodaysLessons(): Collection
    {
        return Lesson::with([
            'group:id,name',
            'group.course:id,title',
            'teacher.user:id,name,surname,photo',
        ])
            ->today()
            ->orderBy('start_time')
            ->get();
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  RECENT PAYMENTS
    // ─────────────────────────────────────────────────────────────────────────

    public function getRecentPayments(int $limit = 5): Collection
    {
        return Payment::paid()
            ->with([
                'payable' => fn ($q) => $q->with('user:id,name,surname'),
            ])
            ->latest('paid_at')
            ->limit($limit)
            ->get();
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  ACTIVE GROUPS
    // ─────────────────────────────────────────────────────────────────────────

    public function getActiveGroups(int $perPage = 8): LengthAwarePaginator
    {
        return Group::with([
            'course:id,title',
            'teachers.user:id,name,surname,photo',
        ])
            ->withCount('activeStudents')
            ->where('is_active', true)
            ->latest()
            ->paginate($perPage);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  OPERATIONAL ALERTS
    // ─────────────────────────────────────────────────────────────────────────

    public function getFullGroupsCount(): int
    {
        return Group::where('is_active', true)
            ->withCount('activeStudents')
            ->get()
            ->filter(fn ($g) => $g->capacity > 0 && $g->active_students_count >= $g->capacity)
            ->count();
    }

    public function getDebtStudentsCount(): int
    {
        return Student::where('balance', '<=', 0)->count();
    }

    public function getUnteachedLessonsToday(): int
    {
        return Lesson::today()->whereNull('teacher_id')->count();
    }
}