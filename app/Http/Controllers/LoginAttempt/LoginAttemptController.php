<?php

namespace App\Http\Controllers\LoginAttempt;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginAttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginAttempt::with('user')->latest('attempted_at');

        // Qidiruv
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Holat filtri
        if ($request->get('status') === 'success') {
            $query->successful();
        } elseif ($request->get('status') === 'failed') {
            $query->failed();
        } elseif ($request->get('status') === 'blocked') {
            $query->blocked();
        }

        // Risk darajasi filtri
        if ($risk = $request->get('risk')) {
            match ($risk) {
                'critical' => $query->where('risk_score', '>=', 80),
                'high'     => $query->whereBetween('risk_score', [60, 79]),
                'medium'   => $query->whereBetween('risk_score', [30, 59]),
                'low'      => $query->where('risk_score', '<', 30),
                default    => null,
            };
        }

        // Sana filtri
        if ($date = $request->get('date')) {
            $query->whereDate('attempted_at', $date);
        } elseif ($from = $request->get('from')) {
            $query->whereDate('attempted_at', '>=', $from);
            if ($to = $request->get('to')) {
                $query->whereDate('attempted_at', '<=', $to);
            }
        }

        $attempts = $query->paginate(25)->withQueryString();

        // Statistika (bugun)
        $today = Carbon::today();
        $stats = [
            'total_today'     => LoginAttempt::whereDate('attempted_at', $today)->count(),
            'failed_today'    => LoginAttempt::failed()->whereDate('attempted_at', $today)->count(),
            'success_today'   => LoginAttempt::successful()->whereDate('attempted_at', $today)->count(),
            'blocked_today'   => LoginAttempt::blocked()->whereDate('attempted_at', $today)->count(),
            'high_risk_today' => LoginAttempt::highRisk()->whereDate('attempted_at', $today)->count(),
            'unique_ips'      => LoginAttempt::whereDate('attempted_at', $today)->distinct('ip_address')->count('ip_address'),
        ];

        // Eng shubhali IPlar (bugun, 3+ xato)
        $suspiciousIps = LoginAttempt::failed()
            ->whereDate('attempted_at', $today)
            ->selectRaw('ip_address, COUNT(*) as attempts, MAX(risk_score) as max_risk')
            ->groupBy('ip_address')
            ->having('attempts', '>=', 3)
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        // Eng ko'p urinilgan phonlar (bugun)
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
            'stats',
            'suspiciousIps',
            'suspiciousPhones'
        ));
    }

    /**
     * Bitta IP uchun batafsil ko'rinish
     */
    public function showIp(Request $request, string $ip)
    {
        $attempts = LoginAttempt::forIp($ip)
            ->with('user')
            ->latest('attempted_at')
            ->paginate(30)
            ->withQueryString();

        $ipStats = [
            'total'         => LoginAttempt::forIp($ip)->count(),
            'failed'        => LoginAttempt::forIp($ip)->failed()->count(),
            'successful'    => LoginAttempt::forIp($ip)->successful()->count(),
            'blocked'       => LoginAttempt::forIp($ip)->blocked()->count(),
            'unique_phones' => LoginAttempt::forIp($ip)->distinct('phone')->count('phone'),
            'first_seen'    => LoginAttempt::forIp($ip)->oldest('attempted_at')->value('attempted_at'),
            'last_seen'     => LoginAttempt::forIp($ip)->latest('attempted_at')->value('attempted_at'),
            'max_risk'      => LoginAttempt::forIp($ip)->max('risk_score'),
            'is_blocked_now' => LoginAttempt::shouldBlockIp($ip),
        ];

        return view('Auth.LoginAttempt.show-ip', compact('attempts', 'ip', 'ipStats'));
    }

    /**
     * Bitta phone uchun batafsil ko'rinish
     */
    public function showPhone(Request $request, string $phone)
    {
        $attempts = LoginAttempt::forPhone($phone)
            ->with('user')
            ->latest('attempted_at')
            ->paginate(30)
            ->withQueryString();

        $phoneStats = [
            'total'           => LoginAttempt::forPhone($phone)->count(),
            'failed'          => LoginAttempt::forPhone($phone)->failed()->count(),
            'successful'      => LoginAttempt::forPhone($phone)->successful()->count(),
            'unique_ips'      => LoginAttempt::forPhone($phone)->distinct('ip_address')->count('ip_address'),
            'first_seen'      => LoginAttempt::forPhone($phone)->oldest('attempted_at')->value('attempted_at'),
            'last_seen'       => LoginAttempt::forPhone($phone)->latest('attempted_at')->value('attempted_at'),
            'is_blocked_now'  => LoginAttempt::shouldBlockPhone($phone),
        ];

        return view('Auth.LoginAttempt.show-phone', compact('attempts', 'phone', 'phoneStats'));
    }

    /**
     * Eski yozuvlarni o'chirish
     */
    public function destroyOld(Request $request)
    {
        $days  = (int) $request->get('days', 30);
        $count = LoginAttempt::cleanOldAttempts($days);
        return back()->with('success', "{$count} ta eski login urinish o'chirildi");
    }

    /**
     * Bitta yozuvni o'chirish
     */
    public function destroy(int $id)
    {
        LoginAttempt::findOrFail($id)->delete();
        return back()->with('success', 'Yozuv o\'chirildi');
    }
}
