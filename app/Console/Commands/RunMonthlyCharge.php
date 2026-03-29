<?php

namespace App\Console\Commands;

use App\Models\ChargeLog;
use App\Services\Payment\MonthlyChargeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RunMonthlyCharge extends Command
{
    protected $signature = 'charge:monthly
                            {--month= : YYYY-MM formatida, bo\'sh bo\'lsa o\'tgan oy}
                            {--force  : Allaqachon charge qilingan bo\'lsa ham qayta ishlatish}';

    protected $description = 'Oylik dars haqi studentlar balansidan yechish';

    public function __construct(private readonly MonthlyChargeService $service)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // ── Oyni aniqlash ─────────────────────────────────────────────────
        if ($this->option('month')) {
            try {
                $targetMonth = Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth();
            } catch (\Exception) {
                $this->error("Noto'g'ri format! Misol: --month=2025-03");
                return self::FAILURE;
            }
        } else {
            $targetMonth = Carbon::now()->subMonth()->startOfMonth();
        }

        // ── Kelajak oy tekshiruvi ──────────────────────────────────────────
        if ($targetMonth->isFuture()) {
            $this->error("Kelajak oy uchun charge qilib bo'lmaydi!");
            return self::FAILURE;
        }

        // ── Allaqachon qilinganmi? ─────────────────────────────────────────
        $existingLog = ChargeLog::where('charge_month', $targetMonth)->first();

        if ($existingLog && !$this->option('force')) {
            $this->warn("{$targetMonth->format('Y F')} oyi allaqachon charge qilingan!");
            $this->table(
                ['Charged', 'Skipped', 'No payment', 'Errors', 'Summa'],
                [[
                    $existingLog->charged_count,
                    $existingLog->skipped_count,
                    $existingLog->no_payment_count,
                    $existingLog->error_count,
                    number_format($existingLog->total_amount, 0, '.', ' ') . ' so\'m',
                ]]
            );
            $this->line("Qayta ishlatish uchun: php artisan charge:monthly --force");
            return self::SUCCESS;
        }

        // ── Boshlash ───────────────────────────────────────────────────────
        $this->info("══════════════════════════════════════════");
        $this->info("  Oylik Charge: {$targetMonth->format('Y — F')}");
        $this->info("══════════════════════════════════════════");

        $bar = $this->output->createProgressBar();
        $bar->setFormat(' %current% student qayta ishlandi [%bar%] %elapsed:6s%');
        $bar->start();

        $stats = $this->service->runMonthlyCharge($targetMonth);

        $bar->finish();
        $this->newLine(2);

        // ── Natija chiqarish ───────────────────────────────────────────────
        $this->table(
            ['✅ Charged', '⏭ Skipped', '❌ No payment', '💥 Errors', '💰 Jami summa'],
            [[
                count($stats['charged']),
                count($stats['skipped']),
                count($stats['no_payment']),
                count($stats['errors']),
                number_format($stats['total_amount'], 0, '.', ' ') . ' so\'m',
            ]]
        );

        // ── Xatoliklar ─────────────────────────────────────────────────────
        if (count($stats['errors'])) {
            $this->newLine();
            $this->error("Xatoliklar:");
            foreach ($stats['errors'] as $error) {
                $this->line("  → $error");
            }
        }

        // ── ChargeLog saqlash ──────────────────────────────────────────────
        ChargeLog::updateOrCreate(
            ['charge_month' => $targetMonth],
            [
                'charged_count'    => count($stats['charged']),
                'skipped_count'    => count($stats['skipped']),
                'no_payment_count' => count($stats['no_payment']),
                'error_count'      => count($stats['errors']),
                'total_amount'     => $stats['total_amount'],
                'details'          => $stats,
                'created_by'       => null, // cron — user yo'q
                'run_at'           => now(),
            ]
        );

        $this->newLine();
        $this->info("✅ Charge muvaffaqiyatli yakunlandi!");

        return count($stats['errors']) ? self::FAILURE : self::SUCCESS;
    }
}