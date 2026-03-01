<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // $notifications = Auth::user()
        //     ->notifications()
        //     ->latest()
        //     ->paginate(20);
        //     // dd($notifications);

        // return view('Notification.index', compact('notifications'));
        $filter = $request->query('filter', 'all');

        $query = Auth::user()->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15);
        $total    = Auth::user()->notifications()->count();
        $unread   = Auth::user()->unreadNotifications()->count();
        $read     = $total - $unread;
        $todayCount = Auth::user()->notifications()->whereDate('created_at', today())->count();

        return view('Notification.index', compact('notifications','total','unread','read','todayCount'));
    }

    /**
     * Mark a single notification as read and redirect to its action_url.
     */
    public function markRead(string $id)
    {
        // dd($id);
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $actionUrl = $notification->data['action_url'] ?? route('dashboard');

        return redirect($actionUrl);
    }

    /**
     * Mark every notification as read.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
