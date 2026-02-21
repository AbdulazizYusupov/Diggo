<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        return view('Panel.Student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id)
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'address' => 'nullable|string|max:500',
        ]);

        // User ma'lumotlarini yangilash
        $user->update([
            'name' => $validated['name'],
            'surname' => $validated['surname'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Profil muvaffaqiyatli yangilandi');
    }

    public function updateParentInfo(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        $validated = $request->validate([
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $student->update($validated);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Ota-ona ma\'lumotlari yangilandi');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Joriy parolni tekshirish
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Joriy parol noto\'g\'ri'
            ]);
        }

        // Yangi parolni o'rnatish
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('student.profile')
            ->with('success', 'Parol muvaffaqiyatli o\'zgartirildi');
    }

    public function statistics()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Umumiy statistika
        $stats = [
            'enrollment_date' => $student->enrollment_date,
            'total_days' => $student->enrollment_date 
                ? now()->diffInDays($student->enrollment_date) 
                : 0,
            
            // Darslar
            'total_lessons' => $student->attendances()->count(),
            'present_lessons' => $student->attendances()->where('status', 'present')->count(),
            'absent_lessons' => $student->attendances()->where('status', 'absent')->count(),
            'late_lessons' => $student->attendances()->where('status', 'late')->count(),
            
            // Coins
            'total_coins_earned' => $student->coinTransactions()
                ->where('coins', '>', 0)
                ->sum('coins'),
            'total_coins_spent' => abs($student->coinTransactions()
                ->where('coins', '<', 0)
                ->sum('coins')),
            
            // Payments
            'total_paid' => $student->payments()
                ->where('status', 'paid')
                ->sum('net_amount'),
            'total_payments_count' => $student->payments()
                ->where('status', 'paid')
                ->count(),
            
            // Groups
            'total_groups' => $student->groups()->count(),
            'active_groups' => $student->groups()
                ->wherePivot('status', 'active')
                ->count(),
            'completed_groups' => $student->groups()
                ->wherePivot('status', 'completed')
                ->count(),
            
            // Scores
            'avg_homework_score' => round($student->attendances()
                ->whereNotNull('homework_score')
                ->avg('homework_score') ?? 0, 1),
            'avg_activity_score' => round($student->attendances()
                ->whereNotNull('activity_score')
                ->avg('activity_score') ?? 0, 1),
        ];

        // Yillik tarixiy ma'lumotlar
        $yearlyData = $this->getYearlyData($student);

        return view('Panel.Student.statistics', compact('student', 'stats', 'yearlyData'));
    }

    private function getYearlyData($student)
    {
        $currentYear = now()->year;
        $years = range($currentYear, max($currentYear - 4, $student->enrollment_date?->year ?? $currentYear));

        $data = [];
        foreach ($years as $year) {
            $data[$year] = [
                'lessons' => $student->attendances()
                    ->whereHas('lesson', function ($q) use ($year) {
                        $q->whereYear('lesson_date', $year);
                    })
                    ->count(),
                'present' => $student->attendances()
                    ->where('status', 'present')
                    ->whereHas('lesson', function ($q) use ($year) {
                        $q->whereYear('lesson_date', $year);
                    })
                    ->count(),
                'coins' => $student->coinTransactions()
                    ->whereYear('transaction_date', $year)
                    ->where('coins', '>', 0)
                    ->sum('coins'),
                'payments' => $student->payments()
                    ->where('status', 'paid')
                    ->whereYear('payment_month', $year)
                    ->sum('net_amount'),
            ];
        }

        return $data;
    }
}
