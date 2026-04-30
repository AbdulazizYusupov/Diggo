<?php

namespace App\Jobs;

use App\Services\Payment\MonthlyChargeService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunMonthlyChargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Carbon $month) {}

    public function handle(MonthlyChargeService $service): void
    {
        Log::info("Starting monthly charge for {$this->month->format('Y-m')}");

        try {
            $result = $service->runMonthlyCharge($this->month);

            Log::info("Monthly charge completed", [
                'month' => $this->month->format('Y-m'),
                'charged_students' => $result['charged'],
                'total_amount' => $result['total_amount'],
            ]);
        } catch (\Exception $e) {
            Log::error("Monthly charge failed for {$this->month->format('Y-m')}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw to mark job as failed
        }
    }
}
