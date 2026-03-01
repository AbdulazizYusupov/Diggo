<?php

namespace App\Http\Controllers\LoginAttempt;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class LoginAttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginAttempt::with('user')->latest('attempted_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->get('status') === 'success') {
            $query->successful();
        } elseif ($request->get('status') === 'failed') {
            $query->failed();
        } elseif ($request->get('status') === 'blocked') {
            $query->blocked();
        }

        if ($risk = $request->get('risk')) {
            match ($risk) {
                'critical' => $query->where('risk_score', '>=', 80),
                'high'     => $query->whereBetween('risk_score', [60, 79]),
                'medium'   => $query->whereBetween('risk_score', [30, 59]),
                'low'      => $query->where('risk_score', '<', 30),
                default    => null,
            };
        }

        if ($date = $request->get('date')) {
            $query->whereDate('attempted_at', $date);
        } elseif ($from = $request->get('from')) {
            $query->whereDate('attempted_at', '>=', $from);
            if ($to = $request->get('to')) {
                $query->whereDate('attempted_at', '<=', $to);
            }
        }

        $attempts = $query->paginate(25)->withQueryString();

        $today = Carbon::today();

        // Blade @php o'rniga controllerda tayyorlanadi
        $statCards = [
            ['label' => 'Bugun jami',     'value' => LoginAttempt::whereDate('attempted_at', $today)->count(),                                    'color' => 'blue'],
            ['label' => 'Muvaffaqiyatli', 'value' => LoginAttempt::successful()->whereDate('attempted_at', $today)->count(),                       'color' => 'emerald'],
            ['label' => 'Xato',           'value' => LoginAttempt::failed()->whereDate('attempted_at', $today)->count(),                           'color' => 'red'],
            ['label' => 'Bloklangan',     'value' => LoginAttempt::blocked()->whereDate('attempted_at', $today)->count(),                          'color' => 'orange'],
            ['label' => 'Yuqori risk',    'value' => LoginAttempt::highRisk()->whereDate('attempted_at', $today)->count(),                         'color' => 'rose'],
            ['label' => 'Unique IPlar',   'value' => LoginAttempt::whereDate('attempted_at', $today)->distinct('ip_address')->count('ip_address'), 'color' => 'violet'],
        ];

        $colorMap = [
            'blue'    => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700/50 text-blue-700 dark:text-blue-300',
            'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-700/50 text-emerald-700 dark:text-emerald-300',
            'red'     => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-700/50 text-red-700 dark:text-red-300',
            'orange'  => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-700/50 text-orange-700 dark:text-orange-300',
            'rose'    => 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-700/50 text-rose-700 dark:text-rose-300',
            'violet'  => 'bg-violet-50 dark:bg-violet-900/20 border-violet-200 dark:border-violet-700/50 text-violet-700 dark:text-violet-300',
        ];

        $suspiciousIps = LoginAttempt::failed()
            ->whereDate('attempted_at', $today)
            ->selectRaw('ip_address, COUNT(*) as attempts, MAX(risk_score) as max_risk')
            ->groupBy('ip_address')
            ->having('attempts', '>=', 3)
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        $suspiciousPhones = LoginAttempt::failed()
            ->whereDate('attempted_at', $today)
            ->selectRaw('phone, COUNT(*) as attempts')
            ->groupBy('phone')
            ->having('attempts', '>=', 3)
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        return view('Auth.LoginAttempt.index', compact(
            'attempts',
            'statCards',
            'colorMap',
            'suspiciousIps',
            'suspiciousPhones'
        ));
    }

    public function showIp(Request $request, string $ip)
    {
        $attempts = LoginAttempt::forIp($ip)
            ->with('user')
            ->latest('attempted_at')
            ->paginate(30)
            ->withQueryString();

        $ipStats = [
            'total'          => LoginAttempt::forIp($ip)->count(),
            'failed'         => LoginAttempt::forIp($ip)->failed()->count(),
            'successful'     => LoginAttempt::forIp($ip)->successful()->count(),
            'blocked'        => LoginAttempt::forIp($ip)->blocked()->count(),
            'unique_phones'  => LoginAttempt::forIp($ip)->distinct('phone')->count('phone'),
            'first_seen'     => LoginAttempt::forIp($ip)->oldest('attempted_at')->value('attempted_at'),
            'last_seen'      => LoginAttempt::forIp($ip)->latest('attempted_at')->value('attempted_at'),
            'max_risk'       => LoginAttempt::forIp($ip)->max('risk_score') ?? 0,
            'is_blocked_now' => LoginAttempt::shouldBlockIp($ip),
        ];

        $relatedPhones = LoginAttempt::forIp($ip)->distinct('phone')->pluck('phone');

        return view('Auth.LoginAttempt.show-ip', compact('attempts', 'ip', 'ipStats', 'relatedPhones'));
    }

    public function showPhone(Request $request, string $phone)
    {
        $attempts = LoginAttempt::forPhone($phone)
            ->with('user')
            ->latest('attempted_at')
            ->paginate(30)
            ->withQueryString();

        $phoneStats = [
            'total'          => LoginAttempt::forPhone($phone)->count(),
            'failed'         => LoginAttempt::forPhone($phone)->failed()->count(),
            'successful'     => LoginAttempt::forPhone($phone)->successful()->count(),
            'unique_ips'     => LoginAttempt::forPhone($phone)->distinct('ip_address')->count('ip_address'),
            'first_seen'     => LoginAttempt::forPhone($phone)->oldest('attempted_at')->value('attempted_at'),
            'last_seen'      => LoginAttempt::forPhone($phone)->latest('attempted_at')->value('attempted_at'),
            'is_blocked_now' => LoginAttempt::shouldBlockPhone($phone),
        ];

        $relatedIps = LoginAttempt::forPhone($phone)->distinct('ip_address')->pluck('ip_address');

        return view('Auth.LoginAttempt.show-phone', compact('attempts', 'phone', 'phoneStats', 'relatedIps'));
    }

    /**
     * UNBLOCK Phone:
     * - DB da o'sha phonega oid barcha failed yozuvlarni o'chiradi
     * - RateLimiter cache ni barcha bog'liq IP+phone kalitlari uchun tozalaydi
     */
    public function unblockPhone(string $phone)
    {
        $relatedIps = LoginAttempt::forPhone($phone)
            ->distinct('ip_address')
            ->pluck('ip_address');

        $deleted = LoginAttempt::forPhone($phone)
            ->where('successful', false)
            ->delete();

        foreach ($relatedIps as $ip) {
            RateLimiter::clear(strtolower($phone) . '|' . $ip);
        }

        return back()->with('success', "'{$phone}' raqami blokdan ochildi. {$deleted} ta yozuv o'chirildi.");
    }

    /**
     * UNBLOCK IP:
     * - DB da o'sha IPga oid barcha failed yozuvlarni o'chiradi
     * - RateLimiter cache ni barcha bog'liq phone+IP kalitlari uchun tozalaydi
     */
    public function unblockIp(string $ip)
    {
        $relatedPhones = LoginAttempt::forIp($ip)
            ->distinct('phone')
            ->pluck('phone');

        $deleted = LoginAttempt::forIp($ip)
            ->where('successful', false)
            ->delete();

        foreach ($relatedPhones as $phone) {
            RateLimiter::clear(strtolower($phone) . '|' . $ip);
        }

        return back()->with('success', "'{$ip}' IP manzili blokdan ochildi. {$deleted} ta yozuv o'chirildi.");
    }

    public function destroyOld(Request $request)
    {
        $days  = (int) $request->get('days', 30);
        $count = LoginAttempt::cleanOldAttempts($days);
        return back()->with('success', "{$count} ta eski login urinish o'chirildi");
    }

    public function destroy(int $id)
    {
        LoginAttempt::findOrFail($id)->delete();
        return back()->with('success', 'Yozuv o\'chirildi');
    }
}