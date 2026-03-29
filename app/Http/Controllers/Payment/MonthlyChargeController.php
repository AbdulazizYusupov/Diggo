<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ChargeLog;
use App\Services\Payment\MonthlyChargeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyChargeController extends Controller
{
    public function __construct(
        private readonly MonthlyChargeService $service
    ) {}

    /**
     * Sahifani ko'rsatish — tarix va preview bilan
     */
    public function index()
    {
        // Oxirgi 6 oylik charge tarixi
        $logs = ChargeLog::with('createdBy')
            ->latest()
            ->take(10)
            ->get();

        // Preview: bu oy uchun charge qilinadigan studentlar soni
        $preview = $this->service->getPreview(Carbon::now()->subMonth()->startOfMonth());

        return view('Payment.monthly-charge', compact('logs', 'preview'));
    }

    /**
     * Manual charge ishlatish
     */
    public function run(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $targetMonth = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();

        // Kelajak oy uchun charge qilish mumkin emas
        if ($targetMonth->isFuture()) {
            return back()->with('error', "Kelajak oy uchun charge qilib bo'lmaydi!");
        }

        // Allaqachon charge qilinganmi?
        $existingLog = ChargeLog::where('charge_month', $targetMonth)->first();
        if ($existingLog && !$request->boolean('force')) {
            return back()->with(
                'warning',
                "{$targetMonth->format('Y F')} oyi allaqachon charge qilingan! 
                 Force mode uchun checkboxni belgilang."
            );
        }

        $stats = $this->service->runMonthlyCharge($targetMonth);

        // Log saqlash
        ChargeLog::updateOrCreate(
            ['charge_month' => $targetMonth],
            [
                'charged_count'    => count($stats['charged']),
                'skipped_count'    => count($stats['skipped']),
                'no_payment_count' => count($stats['no_payment']),
                'error_count'      => count($stats['errors']),
                'total_amount'     => $stats['total_amount'],
                'details'          => $stats,
                'created_by'       => Auth::id(),
                'run_at'           => now(),
            ]
        );

        $message = sprintf(
            "✅ %s oyi charge qilindi! Charged: %d | Skipped: %d | No payment: %d | Errors: %d",
            $targetMonth->format('Y F'),
            count($stats['charged']),
            count($stats['skipped']),
            count($stats['no_payment']),
            count($stats['errors']),
        );

        return back()->with('success', $message)->with('stats', $stats);
    }
}
