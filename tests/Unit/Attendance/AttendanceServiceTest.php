<?php

namespace Tests\Unit\Attendance;

use App\Models\Attendance;
use App\Services\Attendance\AttendanceService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceServiceTest extends TestCase
{
    // Eslatma: RefreshDatabase traitini ishlatsangiz, test ma'lumotlar bazasi bilan ishlaydi.
    // Agar faqat sof mantiqni (Logic) test qilmoqchi bo'lsangiz, mock-lardan foydalanish kerak.
    use RefreshDatabase;

    protected $attendanceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->attendanceService = new AttendanceService();
    }

    /**
     * Test coin calculation for present status.
     */
    public function test_calc_daily_coins_for_present()
    {
        $attendance = new Attendance([
            'status' => 'present',
            'homework_score' => 3,
            'activity_score' => 2,
        ]);

        // Present (2) + HW (3) + Activity (2) = 7 coins
        $result = $this->attendanceService->calcDailyCoins($attendance);
        
        $this->assertEquals(7, $result);
    }

    /**
     * Test coin calculation for late status (under 15 mins).
     */
    public function test_calc_daily_coins_for_late_under_15()
    {
        $attendance = new Attendance([
            'status' => 'late',
            'late_minutes' => 10,
            'homework_score' => 0,
            'activity_score' => 0,
        ]);

        // Late < 15 (2) + 0 + 0 = 2 coins
        $result = $this->attendanceService->calcDailyCoins($attendance);
        
        $this->assertEquals(2, $result);
    }

    /**
     * Test coin calculation for late status (between 15 and 30 mins).
     */
    public function test_calc_daily_coins_for_late_over_15()
    {
        $attendance = new Attendance([
            'status' => 'late',
            'late_minutes' => 20,
            'homework_score' => 1,
            'activity_score' => 1,
        ]);

        // Late 15-30 (1) + HW (1) + Activity (1) = 3 coins
        $result = $this->attendanceService->calcDailyCoins($attendance);
        
        $this->assertEquals(3, $result);
    }

    /**
     * Test coin calculation for absent status.
     */
    public function test_calc_daily_coins_for_absent()
    {
        $attendance = new Attendance([
            'status' => 'absent',
            'homework_score' => 3, // Should be ignored
            'activity_score' => 3, // Should be ignored
        ]);

        // Absent (0)
        $result = $this->attendanceService->calcDailyCoins($attendance);
        
        $this->assertEquals(0, $result);
    }

    /**
     * Test coin score capping (max 3).
     */
    public function test_calc_daily_coins_score_capping()
    {
        $attendance = new Attendance([
            'status' => 'present',
            'homework_score' => 10, // Should be capped at 3
            'activity_score' => 10, // Should be capped at 3
        ]);

        // Present (2) + HW (3) + Activity (3) = 8 coins
        $result = $this->attendanceService->calcDailyCoins($attendance);
        
        $this->assertEquals(8, $result);
    }
}
