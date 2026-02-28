<?php

namespace App\Http\Controllers\UserActivityLog;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivityLog::with('user');

        // Search by user name/phone
        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%");
            });
        }

        // Filter by action
        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }

        // Filter by role
        if ($role = $request->get('role')) {
            $query->whereHas('user.roles', fn($q) => $q->where('name', $role));
        }

        // Filter by date
        if ($date = $request->get('date')) {
            $query->whereDate('logged_at', $date);
        } elseif ($from = $request->get('from')) {
            $query->whereDate('logged_at', '>=', $from);
            if ($to = $request->get('to')) {
                $query->whereDate('logged_at', '<=', $to);
            }
        }

        // Filter by device
        if ($device = $request->get('device')) {
            $query->where('device', $device);
        }

        $logs = $query->orderBy('logged_at', 'desc')->paginate(25)->withQueryString();

        // Stats
        $today = Carbon::today();
        $stats = [
            'total_today'   => UserActivityLog::whereDate('logged_at', $today)->count(),
            'logins_today'  => UserActivityLog::whereDate('logged_at', $today)->where('action', 'login')->count(),
            'logouts_today' => UserActivityLog::whereDate('logged_at', $today)->where('action', 'logout')->count(),
            'unique_users'  => UserActivityLog::whereDate('logged_at', $today)->distinct('user_id')->count('user_id'),
            'online_now'    => $this->getOnlineUsers()->count(),
        ];

        $roles = ['admin', 'director', 'teacher', 'student', 'staff'];

        return view('UserActivityLogs.index', compact('logs', 'stats', 'roles'));
    }

    public function show(Request $request, $userId)
    {
        $user = User::with('roles')->findOrFail($userId);

        $query = UserActivityLog::where('user_id', $userId);

        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }

        if ($from = $request->get('from')) {
            $query->whereDate('logged_at', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('logged_at', '<=', $to);
        }

        $logs = $query->orderBy('logged_at', 'desc')->paginate(20)->withQueryString();

        // Bu user stats
        $stats = [
            'total_logins'   => UserActivityLog::where('user_id', $userId)->where('action', 'login')->count(),
            'last_login'     => UserActivityLog::where('user_id', $userId)->where('action', 'login')->latest('logged_at')->first()?->logged_at,
            'last_logout'    => UserActivityLog::where('user_id', $userId)->where('action', 'logout')->latest('logged_at')->first()?->logged_at,
            'devices_used'   => UserActivityLog::where('user_id', $userId)->distinct('device')->pluck('device'),
            'browsers_used'  => UserActivityLog::where('user_id', $userId)->distinct('browser')->pluck('browser'),
        ];

        return view('UserActivityLogs.show', compact('user', 'logs', 'stats'));
    }

    public function destroy($id)
    {
        UserActivityLog::findOrFail($id)->delete();
        return back()->with('success', 'Log o\'chirildi');
    }

    public function destroyAll(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $count = UserActivityLog::where('logged_at', '<', now()->subDays($days))->delete();
        return back()->with('success', "{$count} ta eski log o'chirildi");
    }

    // Hozir online (so'nggi 15 daqiqada login qilgan, logout qilmagan)
    private function getOnlineUsers()
    {
        $recentLogins = UserActivityLog::where('action', 'login')
            ->where('logged_at', '>=', now()->subMinutes(15))
            ->distinct('user_id')
            ->pluck('user_id');

        $recentLogouts = UserActivityLog::where('action', 'logout')
            ->where('logged_at', '>=', now()->subMinutes(15))
            ->distinct('user_id')
            ->pluck('user_id');

        return $recentLogins->diff($recentLogouts);
    }
    public function online()
    {
        $onlineUserIds = $this->getOnlineUserIds();

        $users = User::with([
            'roles',
            'latestLogin' => fn($q) => $q->where('action', 'login')->latest('logged_at')
        ])
            ->whereIn('id', $onlineUserIds)
            ->get();

        // Har bir user ning so'nggi login ma'lumotlari
        $lastLogs = UserActivityLog::where('action', 'login')
            ->whereIn('user_id', $onlineUserIds)
            ->whereIn('id', function ($q) use ($onlineUserIds) {
                $q->selectRaw('MAX(id)')
                    ->from('user_activity_logs')
                    ->where('action', 'login')
                    ->whereIn('user_id', $onlineUserIds)
                    ->groupBy('user_id');
            })
            ->get()
            ->keyBy('user_id');

        return view('UserActivityLogs.online', compact('users', 'lastLogs'));
    }

    private function getOnlineUserIds(): Collection
    {
        $recentLogins = UserActivityLog::where('action', 'login')
            ->where('logged_at', '>=', now()->subMinutes(15))
            ->distinct('user_id')
            ->pluck('user_id');

        $recentLogouts = UserActivityLog::where('action', 'logout')
            ->where('logged_at', '>=', now()->subMinutes(15))
            ->distinct('user_id')
            ->pluck('user_id');

        return $recentLogins->diff($recentLogouts);
    }
}
