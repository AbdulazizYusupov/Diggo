<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentActivityController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  Constants
    // ─────────────────────────────────────────────────────────────

    private const TYPES = [
        'homework' => [
            'label'    => 'Uy vazifa',
            'gradient' => 'from-blue-500 to-blue-700',
            'bg'       => 'bg-blue-100 dark:bg-blue-500/20',
            'text'     => 'text-blue-700 dark:text-blue-300',
            'border'   => 'border-blue-200 dark:border-blue-500/30',
            'icon_bg'  => 'bg-blue-600',
            'icon'     => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        ],
        'exam' => [
            'label'    => 'Imtihon',
            'gradient' => 'from-rose-500 to-rose-700',
            'bg'       => 'bg-rose-100 dark:bg-rose-500/20',
            'text'     => 'text-rose-700 dark:text-rose-300',
            'border'   => 'border-rose-200 dark:border-rose-500/30',
            'icon_bg'  => 'bg-rose-600',
            'icon'     => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        ],
        'competition' => [
            'label'    => 'Musobaqa',
            'gradient' => 'from-purple-500 to-purple-700',
            'bg'       => 'bg-amber-100 dark:bg-amber-500/20',
            'text'     => 'text-amber-700 dark:text-amber-300',
            'border'   => 'border-amber-200 dark:border-amber-500/30',
            'icon_bg'  => 'bg-amber-500',
            'icon'     => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        ],
        'project' => [
            'label'    => 'Loyiha',
            'gradient' => 'from-emerald-500 to-emerald-700',
            'bg'       => 'bg-emerald-100 dark:bg-emerald-500/20',
            'text'     => 'text-emerald-700 dark:text-emerald-300',
            'border'   => 'border-emerald-200 dark:border-emerald-500/30',
            'icon_bg'  => 'bg-emerald-600',
            'icon'     => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        ],
        'other' => [
            'label'    => 'Boshqa',
            'gradient' => 'from-gray-500 to-gray-700',
            'bg'       => 'bg-gray-100 dark:bg-gray-500/20',
            'text'     => 'text-gray-700 dark:text-gray-300',
            'border'   => 'border-gray-200 dark:border-gray-600',
            'icon_bg'  => 'bg-gray-600',
            'icon'     => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        ],
    ];

    private const VALID_TYPES        = ['homework', 'exam', 'competition', 'project', 'other'];
    private const VALID_TIME_FILTERS = ['today', 'upcoming', 'past'];

    // ─────────────────────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────────────────────

    /** Returns the authenticated student model (or null). */
    private function student()
    {
        return Auth::user()?->student;
    }

    /**
     * Base query: only ACTIVE activities visible to this student.
     * Includes their group's activities + global (no-group) activities.
     */
    private function visibleActivitiesQuery()
    {
        $student = $this->student();

        return Activity::query()
            ->with(['group.course'])
            ->where('status', 'active')                     // ← only active activities
            ->where(function ($q) use ($student) {
                if ($student?->group_id) {
                    $q->where('group_id', $student->group_id)
                        ->orWhereNull('group_id');
                } else {
                    $q->whereNull('group_id');
                }
            });
    }

    /**
     * Apply smart ordering:
     *  1. Today first
     *  2. Upcoming next (ASC — nearest first)
     *  3. Past last (DESC — most recent past first)
     */
    private function applyOrdering($query): void
    {
        $query
            ->orderByRaw("CASE WHEN date = CURDATE() THEN 0 WHEN date > CURDATE() THEN 1 ELSE 2 END ASC")
            ->orderByRaw("CASE WHEN date >= CURDATE() THEN date ELSE NULL END ASC")
            ->orderByRaw("CASE WHEN date < CURDATE() THEN date ELSE NULL END DESC");
    }

    /**
     * Compute stats counters from the unfiltered visible scope.
     */
    private function buildStats(): array
    {
        $base = $this->visibleActivitiesQuery();

        return [
            'total'       => (clone $base)->count(),
            'upcoming'    => (clone $base)->whereDate('date', '>=', now()->toDateString())->count(),
            'this_month'  => (clone $base)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
            'homework'    => (clone $base)->where('type', 'homework')->count(),
            'exam'        => (clone $base)->where('type', 'exam')->count(),
            'competition' => (clone $base)->where('type', 'competition')->count(),
        ];
    }

    /**
     * Build the stat card definitions for the view.
     * Zero PHP logic left in Blade.
     */
    private function buildStatItems(array $stats): array
    {
        return [
            [
                'label'  => 'Jami',
                'value'  => $stats['total'],
                'bg'     => 'bg-indigo-50 dark:bg-indigo-500/10',
                'text'   => 'text-indigo-600 dark:text-indigo-400',
                'border' => 'border-indigo-100 dark:border-indigo-500/20',
                'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            ],
            [
                'label'  => 'Kelgusi',
                'value'  => $stats['upcoming'],
                'bg'     => 'bg-emerald-50 dark:bg-emerald-500/10',
                'text'   => 'text-emerald-600 dark:text-emerald-400',
                'border' => 'border-emerald-100 dark:border-emerald-500/20',
                'icon'   => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            [
                'label'  => 'Bu oy',
                'value'  => $stats['this_month'],
                'bg'     => 'bg-sky-50 dark:bg-sky-500/10',
                'text'   => 'text-sky-600 dark:text-sky-400',
                'border' => 'border-sky-100 dark:border-sky-500/20',
                'icon'   => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            ],
            [
                'label'  => 'Uy vazifa',
                'value'  => $stats['homework'],
                'bg'     => 'bg-blue-50 dark:bg-blue-500/10',
                'text'   => 'text-blue-600 dark:text-blue-400',
                'border' => 'border-blue-100 dark:border-blue-500/20',
                'icon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            ],
            [
                'label'  => 'Imtihon',
                'value'  => $stats['exam'],
                'bg'     => 'bg-rose-50 dark:bg-rose-500/10',
                'text'   => 'text-rose-600 dark:text-rose-400',
                'border' => 'border-rose-100 dark:border-rose-500/20',
                'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            ],
            [
                'label'  => 'Musobaqa',
                'value'  => $stats['competition'],
                'bg'     => 'bg-amber-50 dark:bg-amber-500/10',
                'text'   => 'text-amber-600 dark:text-amber-400',
                'border' => 'border-amber-100 dark:border-amber-500/20',
                'icon'   => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            ],
        ];
    }

    /**
     * Resolve type metadata from the TYPES constant.
     * Falls back to 'other' for unknown types.
     */
    private function resolveTypeData(string $type): array
    {
        return self::TYPES[$type] ?? self::TYPES['other'];
    }

    /**
     * Attach all display properties needed by the Blade views
     * directly onto the Activity model instance.
     * Blade contains zero @php calculation blocks as a result.
     */
    private function attachDisplayData(Activity $activity): Activity
    {
        $typeData = $this->resolveTypeData($activity->type);

        // Type display
        $activity->type_label    = $typeData['label'];
        $activity->type_gradient = $typeData['gradient'];
        $activity->type_bg       = $typeData['bg'];
        $activity->type_text     = $typeData['text'];
        $activity->type_border   = $typeData['border'];
        $activity->type_icon_bg  = $typeData['icon_bg'];
        $activity->type_icon     = $typeData['icon'];

        // Date helpers
        $today = now()->startOfDay();
        $date  = $activity->date->copy()->startOfDay();

        $activity->is_today    = $date->isSameDay($today);
        $activity->is_upcoming = $date->isAfter($today);
        $activity->is_past     = $date->isBefore($today);
        $activity->days_left   = $activity->is_upcoming ? (int) $today->diffInDays($date) : null;

        return $activity;
    }

    // ─────────────────────────────────────────────────────────────
    //  index
    // ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search'      => ['nullable', 'string', 'max:100'],
            'type'        => ['nullable', 'string', 'in:' . implode(',', self::VALID_TYPES)],
            'from'        => ['nullable', 'date'],
            'to'          => ['nullable', 'date', 'after_or_equal:from'],
            'time_filter' => ['nullable', 'string', 'in:' . implode(',', self::VALID_TIME_FILTERS)],  // ← NEW
        ]);

        $query = $this->visibleActivitiesQuery();

        // ── Type filter ──────────────────────────────────────────
        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        // ── Date range filters ───────────────────────────────────
        if (! empty($validated['from'])) {
            $query->whereDate('date', '>=', $validated['from']);
        }

        if (! empty($validated['to'])) {
            $query->whereDate('date', '<=', $validated['to']);
        }

        // ── Time filter (today / upcoming / past) ────────────────  ← NEW
        if (! empty($validated['time_filter'])) {
            match ($validated['time_filter']) {
                'today'    => $query->whereDate('date', now()->toDateString()),
                'upcoming' => $query->whereDate('date', '>', now()->toDateString()),
                'past'     => $query->whereDate('date', '<', now()->toDateString()),
            };
        }

        // ── Search ───────────────────────────────────────────────
        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // ── Ordering: today → upcoming ASC → past DESC ───────────  ← NEW
        $this->applyOrdering($query);

        $activities = $query->paginate(12)->withQueryString();

        $activities->getCollection()->transform(
            fn(Activity $a) => $this->attachDisplayData($a)
        );

        $stats     = $this->buildStats();
        $statItems = $this->buildStatItems($stats);

        return view('Panel.Student.activities', compact('activities', 'stats', 'statItems', 'validated'));
    }

    // ─────────────────────────────────────────────────────────────
    //  show
    // ─────────────────────────────────────────────────────────────

    public function show(Activity $activity)
    {
        // Only active activities are accessible
        if ($activity->status !== 'active') {
            abort(404);
        }

        $student = $this->student();

        // Group-specific activity: only members of that group may view
        if ($activity->group_id !== null) {
            if (! $student || $student->group_id !== $activity->group_id) {
                abort(403, 'Bu e\'lonni ko\'rish huquqingiz yo\'q.');
            }
        }

        $activity->load(['group.course', 'creator']);
        $activity = $this->attachDisplayData($activity);

        $myTransaction = null;
        if ($student) {
            $myTransaction = CoinTransaction::query()
                ->where('activity_id', $activity->id)
                ->where('student_id', $student->id)
                ->latest()
                ->first();
        }

        return view('Panel.Student.activities-show', compact('activity', 'myTransaction'));
    }
}