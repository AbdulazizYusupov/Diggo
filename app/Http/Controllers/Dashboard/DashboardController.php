<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Summary Stats ---
        $totalStudents = Student::count();

        $activeGroups = Group::where('is_active', true)->count();

        $monthlyRevenue = Payment::paid()
            ->thisMonth()
            ->sum('net_amount');

        // --- Active Groups Table (with eager-loaded relations) ---
        $groups = Group::with([
            'course:id,title',
            'teachers.user:id,name,surname,photo',
            'activeStudents',
        ])
            ->where('is_active', true)
            ->latest()
            ->paginate(10);

        // --- Attendance rate for current month (present / total) ---
        $attendanceRate = $this->getMonthlyAttendanceRate();

        return view('Dashboard.index', compact(
            'totalStudents',
            'activeGroups',
            'monthlyRevenue',
            'groups',
            'attendanceRate',
        ));
    }

    /**
     * Calculate overall attendance rate for the current month.
     */
    private function getMonthlyAttendanceRate(): float
    {
        $result = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereMonth('lessons.lesson_date', now()->month)
            ->whereYear('lessons.lesson_date', now()->year)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as present
            ', ['present'])
            ->first();

        if (!$result || $result->total === 0) {
            return 0.0;
        }

        return round(($result->present / $result->total) * 100, 1);
    }
}
