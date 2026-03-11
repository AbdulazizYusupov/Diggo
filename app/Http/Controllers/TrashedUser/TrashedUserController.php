<?php

namespace App\Http\Controllers\TrashedUser;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TrashedUserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'role'   => 'nullable|in:student,teacher,staff,director,all',
            'gender' => 'nullable|in:male,female',
        ]);

        $search = trim($request->input('search', ''));
        $role   = $request->input('role', 'all');
        $gender = $request->input('gender');

        $query = User::onlyTrashed()->with([
            'student'  => fn($q) => $q->withTrashed(),
            'teacher'  => fn($q) => $q->withTrashed(),
            'staff'    => fn($q) => $q->withTrashed(),
            'director' => fn($q) => $q->withTrashed(),
        ]);

        if ($role && $role !== 'all') {
            $query->whereHas($role, fn($q) => $q->withTrashed());
        } else {
            $query->where(function ($q) {
                $q->whereHas('student',  fn($s) => $s->withTrashed())
                    ->orWhereHas('teacher',  fn($t) => $t->withTrashed())
                    ->orWhereHas('staff',    fn($s) => $s->withTrashed())
                    ->orWhereHas('director', fn($d) => $d->withTrashed());
            });
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($search) {
            $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(surname, ' ', name, ' ', COALESCE(middle_name, '')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("CONCAT(surname, ' ', COALESCE(middle_name, '')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("CONCAT(name, ' ', COALESCE(middle_name, '')) LIKE ?", ["%{$search}%"]);
            });
        }

        $users = $query->latest('deleted_at')->paginate(15)->withQueryString();

        // roleInfo ni har bir userga biriktiramiz — blade da @php kerak emas
        $users->getCollection()->transform(function ($user) {
            $userRole = match (true) {
                $user->student  !== null => 'student',
                $user->teacher  !== null => 'teacher',
                $user->staff    !== null => 'staff',
                $user->director !== null => 'director',
                default                  => 'unknown',
            };
            $user->roleInfo = roleInfo($userRole);
            return $user;
        });

        // Statistika
        $stats = [
            'total'     => User::onlyTrashed()->count(),
            'students'  => User::onlyTrashed()->whereHas('student',  fn($q) => $q->withTrashed())->count(),
            'teachers'  => User::onlyTrashed()->whereHas('teacher',  fn($q) => $q->withTrashed())->count(),
            'staff'     => User::onlyTrashed()->whereHas('staff',    fn($q) => $q->withTrashed())->count(),
            'directors' => User::onlyTrashed()->whereHas('director', fn($q) => $q->withTrashed())->count(),
        ];

        // Stat kartalar
        $statCards = [
            [
                'label'       => 'Jami',
                'count'       => $stats['total'],
                'role'        => 'all',
                'textClass'   => 'text-rose-600 dark:text-rose-400',
                'activeClass' => 'border-rose-400 dark:border-rose-500 ring-2 ring-rose-200 dark:ring-rose-500/30',
                'active'      => ($role ?? 'all') === 'all',
            ],
            [
                'label'       => "O'quvchilar",
                'count'       => $stats['students'],
                'role'        => 'student',
                'textClass'   => 'text-emerald-600 dark:text-emerald-400',
                'activeClass' => 'border-emerald-400 dark:border-emerald-500 ring-2 ring-emerald-200 dark:ring-emerald-500/30',
                'active'      => $role === 'student',
            ],
            [
                'label'       => "O'qituvchilar",
                'count'       => $stats['teachers'],
                'role'        => 'teacher',
                'textClass'   => 'text-blue-600 dark:text-blue-400',
                'activeClass' => 'border-blue-400 dark:border-blue-500 ring-2 ring-blue-200 dark:ring-blue-500/30',
                'active'      => $role === 'teacher',
            ],
            [
                'label'       => 'Xodimlar',
                'count'       => $stats['staff'],
                'role'        => 'staff',
                'textClass'   => 'text-amber-600 dark:text-amber-400',
                'activeClass' => 'border-amber-400 dark:border-amber-500 ring-2 ring-amber-200 dark:ring-amber-500/30',
                'active'      => $role === 'staff',
            ],
            [
                'label'       => 'Direktorlar',
                'count'       => $stats['directors'],
                'role'        => 'director',
                'textClass'   => 'text-purple-600 dark:text-purple-400',
                'activeClass' => 'border-purple-400 dark:border-purple-500 ring-2 ring-purple-200 dark:ring-purple-500/30',
                'active'      => $role === 'director',
            ],
        ];

        return view('TrashedUser.index', compact(
            'users',
            'stats',
            'statCards',
            'search',
            'role',
            'gender'
        ));
    }

    public function restore(Request $request, int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        // Avval bog'liq modelni tikla, keyin userni
        foreach (['student', 'teacher', 'staff', 'director'] as $relation) {
            $user->{$relation}()->withTrashed()->restore();
        }

        $user->restore();

        return back()->with('success', "{$user->full_name} muvaffaqiyatli tiklandi!");
    }

    public function forceDelete(Request $request, int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $name = $user->full_name;

        // Avval bog'liq modellarni o'chir (foreign key xatosini oldini olish)
        foreach (['student', 'teacher', 'staff', 'director'] as $relation) {
            $user->{$relation}()->withTrashed()->forceDelete();
        }

        // Keyin userni o'chir
        $user->forceDelete();

        return back()->with('success', "{$name} butunlay o'chirildi!");
    }

    public function restoreAll(Request $request)
    {
        $role  = $request->input('role', 'all');
        $query = User::onlyTrashed();

        if ($role !== 'all') {
            $query->whereHas($role, fn($q) => $q->withTrashed());
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->restore();
            foreach (['student', 'teacher', 'staff', 'director'] as $relation) {
                if ($user->{$relation}()->withTrashed()->exists()) {
                    $user->{$relation}()->withTrashed()->restore();
                }
            }
        }

        return back()->with('success', "{$users->count()} ta foydalanuvchi tiklandi!");
    }
}
