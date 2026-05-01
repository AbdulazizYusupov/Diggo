<?php

namespace App\Http\Controllers\Impersonate;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function index(Request $request)
    {
        // Faqat haqiqiy admin
        // Impersonation holatida ham original admin foydalanuvchi roli asosida ko'rishi mumkin.
        $this->ensureRealAdmin(allowWhileImpersonating: true);

        $search = $request->input('search');
        $role   = $request->input('role', 'all');
        $status = $request->input('status', 'all');

        $query = User::query()
            ->with(['roles', 'student', 'teacher', 'director', 'staff'])
            ->withTrashed(); // o'chirilganlarni ham ko'rish uchun

        // Qidiruv
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Rol bo'yicha filtr
        if ($role !== 'all') {
            $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }

        // Status bo'yicha filtr
        if ($status === 'active') {
            $query->where('is_active', true)->whereNull('deleted_at');
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } elseif ($status === 'deleted') {
            $query->onlyTrashed();
        }

        // Joriy adminni ro'yxatdan chiqarish
        $query->where('id', '!=', Auth::id());

        $users = $query->orderBy('surname')->orderBy('name')->paginate(20)->withQueryString();

        // Statistika (barcha userlar uchun)
        $stats = [
            'total'    => User::count(),
            'teachers' => User::whereHas('roles', fn($q) => $q->where('name', 'teacher'))->count(),
            'students' => User::whereHas('roles', fn($q) => $q->where('name', 'student'))->count(),
            'directors' => User::whereHas('roles', fn($q) => $q->where('name', 'director'))->count(),
            'staff'    => User::whereHas('roles', fn($q) => $q->where('name', 'staff'))->count(),
        ];

        return view('Admin.Impersonate.index', compact('users', 'search', 'role', 'status', 'stats'));
    }

    /**
     * Impersonation boshlash — user nomidan kirish
     */
    public function start(User $user)
    {
        $this->ensureRealAdmin();

        // Admin o'zini impersonate qila olmaydi
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Admin foydalanuvchini impersonate qilib bo\'lmaydi!');
        }

        // O'chirilgan yoki faol emasni impersonate qilmaslik
        if (!$user->is_active || $user->trashed()) {
            return back()->with('error', 'Faol bo\'lmagan yoki o\'chirilgan foydalanuvchini impersonate qilib bo\'lmaydi!');
        }

        // Joriy admin ID ni saqlab qolish
        session([
            'impersonate.original_id'   => Auth::id(),
            'impersonate.original_name' => Auth::user()->full_name ?? Auth::user()->name,
            'impersonate.active'        => true,
            'impersonate.target_name'   => $user->full_name ?? $user->name,
            'impersonate.target_role'   => $user->roles->first()?->name ?? 'user',
        ]);

        // User sifatida login qilish
        Auth::loginUsingId($user->id);

        // Rolga qarab redirect
        $redirectUrl = $this->getDashboardRoute($user);

        return redirect($redirectUrl)
            ->with('info', "Siz {$user->surname} {$user->name} nomidan kirdingiziiz!");
    }

    /**
     * Impersonation tugatish — adminga qaytish
     */
    public function leave()
    {
        if (!session('impersonate.active')) {
            return redirect()->route('dashboard');
        }

        $originalId   = session('impersonate.original_id');
        $originalName = session('impersonate.original_name');

        // Session tozalash
        session()->forget([
            'impersonate.original_id',
            'impersonate.original_name',
            'impersonate.active',
            'impersonate.target_name',
            'impersonate.target_role',
        ]);

        // Adminga qaytish
        Auth::loginUsingId($originalId);

        return redirect()->route('impersonate.index')
            ->with('success', "Siz o'z kabinetingizga qaytdingiz, {$originalName}!");
    }

    // ─────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────

    /**
     * Faqat haqiqiy admin (impersonation holatida emas) kira olishi kerak
     */
    private function ensureRealAdmin(bool $allowWhileImpersonating = false): void
    {
        // Impersonation holatida bo'lsa, original adminni aniqlaymiz.
        if (session('impersonate.active')) {
            if (! $allowWhileImpersonating) {
                abort(403, 'Impersonation holatida boshqa userni impersonate qilib bo\'lmaydi!');
            }

            $originalId = session('impersonate.original_id');
            $originalUser = $originalId ? User::find($originalId) : null;

            if (! $originalUser || ! $originalUser->hasRole('admin')) {
                abort(403, 'Impersonation holatida bu sahifaga ruxsat yo\'q!');
            }

            return;
        }

        // Admin role tekshiruvi
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Bu sahifaga faqat adminlar kira oladi!');
        }
    }

    /**
     * Foydalanuvchi roliga qarab dashboard URL qaytarish
     */
    private function getDashboardRoute(User $user): string
    {
        $role = $user->roles->first()?->name;

        return match ($role) {
            'teacher'  => route('teacher.dashboard'),
            'student'  => route('student.dashboard'),
            'director' => route('director.dashboard'),
            'staff'    => route('dashboard'),
            default    => route('dashboard'),
        };
    }
}
