<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentActivityController extends Controller
{
    private array $typeLabels = [
        'homework'    => ['label' => 'Uy vazifa',  'color' => 'blue'],
        'exam'        => ['label' => 'Imtihon',    'color' => 'red'],
        'competition' => ['label' => 'Musobaqa',   'color' => 'yellow'],
        'project'     => ['label' => 'Loyiha',     'color' => 'purple'],
        'other'       => ['label' => 'Boshqa',     'color' => 'gray'],
    ];

    private array $typeColorMap = [
        'blue'   => [
            'bg'       => 'bg-blue-100 dark:bg-blue-900/30',
            'text'     => 'text-blue-700 dark:text-blue-300',
            'border'   => 'border-blue-200 dark:border-blue-700/50',
            'icon_bg'  => 'bg-blue-600',
            'light_bg' => 'from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-800/10',
        ],
        'red'    => [
            'bg'       => 'bg-red-100 dark:bg-red-900/30',
            'text'     => 'text-red-700 dark:text-red-300',
            'border'   => 'border-red-200 dark:border-red-700/50',
            'icon_bg'  => 'bg-red-600',
            'light_bg' => 'from-red-50 to-red-100/50 dark:from-red-900/20 dark:to-red-800/10',
        ],
        'yellow' => [
            'bg'       => 'bg-yellow-100 dark:bg-yellow-900/30',
            'text'     => 'text-yellow-700 dark:text-yellow-300',
            'border'   => 'border-yellow-200 dark:border-yellow-700/50',
            'icon_bg'  => 'bg-yellow-500',
            'light_bg' => 'from-yellow-50 to-yellow-100/50 dark:from-yellow-900/20 dark:to-yellow-800/10',
        ],
        'purple' => [
            'bg'       => 'bg-purple-100 dark:bg-purple-900/30',
            'text'     => 'text-purple-700 dark:text-purple-300',
            'border'   => 'border-purple-200 dark:border-purple-700/50',
            'icon_bg'  => 'bg-purple-600',
            'light_bg' => 'from-purple-50 to-purple-100/50 dark:from-purple-900/20 dark:to-purple-800/10',
        ],
        'gray'   => [
            'bg'       => 'bg-gray-100 dark:bg-gray-700/50',
            'text'     => 'text-gray-700 dark:text-gray-300',
            'border'   => 'border-gray-200 dark:border-gray-600',
            'icon_bg'  => 'bg-gray-600',
            'light_bg' => 'from-gray-50 to-gray-100/50 dark:from-gray-900/20 dark:to-gray-800/10',
        ],
    ];

    public function index(Request $request)
    {
        $student = Auth::user()->student;

        $query = Activity::with('group')
            ->where(function ($q) use ($student) {
                if ($student && $student->group_id) {
                    $q->where('group_id', $student->group_id)
                        ->orWhereNull('group_id');
                } else {
                    $q->whereNull('group_id');
                }
            })
            ->orderBy('date', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $activities = $query->paginate(12)->withQueryString();

        $baseQuery = Activity::where(function ($q) use ($student) {
            if ($student && $student->group_id) {
                $q->where('group_id', $student->group_id)->orWhereNull('group_id');
            } else {
                $q->whereNull('group_id');
            }
        });

        $stats = [
            'total'       => (clone $baseQuery)->count(),
            'upcoming'    => (clone $baseQuery)->whereDate('date', '>=', now())->count(),
            'this_month'  => (clone $baseQuery)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
            'homework'    => (clone $baseQuery)->where('type', 'homework')->count(),
            'exam'        => (clone $baseQuery)->where('type', 'exam')->count(),
            'competition' => (clone $baseQuery)->where('type', 'competition')->count(),
        ];

        $activities->getCollection()->transform(
            fn(Activity $activity) => $this->attachDisplayData($activity)
        );

        return view('Panel.Student.activities', compact('activities', 'stats'));
    }

    public function show(Activity $activity)
    {
        $student = Auth::user()->student;

        if ($activity->group_id !== null) {
            if (! $student || $student->group_id !== $activity->group_id) {
                abort(403, 'Bu e\'lonni ko\'rish huquqingiz yo\'q.');
            }
        }

        $activity->load('group');
        $activity = $this->attachDisplayData($activity);

        return view('Panel.Student.activities-show', compact('activity'));
    }

    /**
     * Barcha display logikasini controllerda hisoblaydi va
     * model ustiga dynamic property sifatida yozadi.
     * Blade da hech qanday @php blok kerak emas.
     */
    private function attachDisplayData(Activity $activity): Activity
    {
        $typeInfo  = $this->typeLabels[$activity->type]           ?? ['label' => $activity->type, 'color' => 'gray'];
        $colorInfo = $this->typeColorMap[$typeInfo['color']]      ?? $this->typeColorMap['gray'];

        $activity->type_label    = $typeInfo['label'];
        $activity->type_color    = $typeInfo['color'];
        $activity->type_bg       = $colorInfo['bg'];
        $activity->type_text     = $colorInfo['text'];
        $activity->type_border   = $colorInfo['border'];
        $activity->type_icon_bg  = $colorInfo['icon_bg'];
        $activity->type_light_bg = $colorInfo['light_bg'];

        $activity->is_today    = $activity->date->isToday();
        $activity->is_upcoming = $activity->date->isFuture();
        $activity->is_past     = $activity->date->isPast() && ! $activity->date->isToday();

        // Kelgusi bo'lsa, necha kun qolganini hisoblaydi
        $activity->days_left = ($activity->is_upcoming && ! $activity->is_today)
            ? $activity->date->diffInDays(now())
            : null;

        return $activity;
    }
}
