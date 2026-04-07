<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ActivityController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    //  SHARED DISPLAY CONFIG — defined once, reused everywhere
    // ═══════════════════════════════════════════════════════════════

    private array $typeLabels = [
        'homework'    => 'Uyga vazifa',
        'exam'        => 'Imtihon',
        'competition' => 'Musobaqa',
        'project'     => 'Loyiha',
        'other'       => 'Boshqa',
    ];

    private array $typeColors = [
        'exam'        => 'red',
        'homework'    => 'blue',
        'competition' => 'purple',
        'project'     => 'green',
        'other'       => 'gray',
    ];

    /** SVG path strings for each activity type icon */
    private array $typeIcons = [
        'homework'    => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'exam'        => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'competition' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        'project'     => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'other'       => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
    ];

    /**
     * Tailwind classes per type: [gradient-from, gradient-to, shadow, bg, text, border]
     */
    private array $typeGradients = [
        'homework'    => ['from-blue-500',    'to-blue-700',    'shadow-blue-200 dark:shadow-blue-900/40',    'bg-blue-100 dark:bg-blue-500/20',    'text-blue-700 dark:text-blue-300',    'border-blue-200 dark:border-blue-500/30'],
        'exam'        => ['from-rose-500',    'to-rose-700',    'shadow-rose-200 dark:shadow-rose-900/40',    'bg-rose-100 dark:bg-rose-500/20',    'text-rose-700 dark:text-rose-300',    'border-rose-200 dark:border-rose-500/30'],
        'competition' => ['from-purple-500',  'to-purple-700',  'shadow-purple-200 dark:shadow-purple-900/40',  'bg-purple-100 dark:bg-purple-500/20',  'text-purple-700 dark:text-purple-300',  'border-purple-200 dark:border-purple-500/30'],
        'project'     => ['from-emerald-500', 'to-emerald-700', 'shadow-emerald-200 dark:shadow-emerald-900/40', 'bg-emerald-100 dark:bg-emerald-500/20', 'text-emerald-700 dark:text-emerald-300', 'border-emerald-200 dark:border-emerald-500/30'],
        'other'       => ['from-gray-500',    'to-gray-700',    'shadow-gray-200 dark:shadow-gray-900/40',    'bg-gray-100 dark:bg-gray-500/20',    'text-gray-700 dark:text-gray-300',    'border-gray-200 dark:border-gray-500/30'],
    ];

    private array $statusConfig = [
        'draft'     => ['label' => 'Qoralama',    'dot' => 'bg-gray-400',    'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-gray-200 dark:border-gray-600'],
        'active'    => ['label' => 'Faol',        'dot' => 'bg-emerald-400', 'class' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30'],
        'completed' => ['label' => 'Tugallangan', 'dot' => 'bg-blue-400',    'class' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 border-blue-200 dark:border-blue-500/30'],
        'cancelled' => ['label' => 'Bekor',       'dot' => 'bg-rose-400',    'class' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border-rose-200 dark:border-rose-500/30'],
    ];

    private array $types    = ['homework', 'exam', 'competition', 'project', 'other'];
    private array $statuses = ['draft', 'active', 'completed', 'cancelled'];

    // ═══════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════════════

    /** Active groups with their course — used on create, edit, index */
    private function getGroups()
    {
        return Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();
    }

    /** Shared validation rules for store & update */
    private function validationRules(Request $request): array
    {
        $timeRequired = ['exam', 'competition'];

        return [
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:' . implode(',', $this->types),
            'status'      => 'required|in:' . implode(',', $this->statuses),
            'group_id'    => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,txt|max:10240',
            'date'        => 'required|date',
            'start_time'  => [
                'nullable',
                'date_format:H:i',
                Rule::requiredIf(fn() => in_array($request->type, $timeRequired)),
            ],
            'due_time'    => [
                'nullable',
                'date_format:H:i',
                'after:start_time',
                Rule::requiredIf(fn() => in_array($request->type, $timeRequired)),
            ],
            'max_coins'   => 'required|integer|min:0|max:1000',
            'is_required' => 'boolean',
        ];
    }

    /** Handle image upload and optionally delete the old one */
    private function handleImageUpload(Request $request, ?string $oldPath = null): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $request->file('image')->store('activities/images', 'public');
    }

    /** Handle document upload and optionally delete the old one */
    private function handleFileUpload(Request $request, ?string $oldPath = null): ?string
    {
        if (!$request->hasFile('file')) {
            return null;
        }

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $request->file('file')->store('activities/files', 'public');
    }

    // ═══════════════════════════════════════════════════════════════
    //  RESOURCE METHODS
    // ═══════════════════════════════════════════════════════════════

    public function index(Request $request): View
    {
        $allowedSorts  = ['name', 'date', 'type', 'status', 'max_coins', 'created_at'];
        $sortField     = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'date';
        $sortDirection = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $activities = Activity::with(['group.course', 'creator'])
            ->when(
                $request->filled('search'),
                fn($q) => $q->where(function ($inner) use ($request) {
                    $inner->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                })
            )
            ->when($request->filled('type'),        fn($q) => $q->where('type', $request->type))
            ->when($request->filled('status'),      fn($q) => $q->where('status', $request->status))
            ->when($request->filled('group_id'),    fn($q) => $q->where('group_id', $request->group_id))
            ->when($request->filled('is_required'), fn($q) => $q->where('is_required', (bool) $request->is_required))
            ->when($request->filled('date_from'),   fn($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->filled('date_to'),     fn($q) => $q->whereDate('date', '<=', $request->date_to))
            ->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        // Single aggregate query for all stats
        $stats = Activity::selectRaw('
            COUNT(*)                                              AS total,
            SUM(CASE WHEN status = "active"   THEN 1 ELSE 0 END) AS active_count,
            SUM(CASE WHEN type   = "homework" THEN 1 ELSE 0 END) AS homework_count,
            SUM(CASE WHEN type   = "exam"     THEN 1 ELSE 0 END) AS exam_count,
            COALESCE(AVG(max_coins), 0)                           AS avg_coins
        ')->first();

        return view('Activity.index', [
            'activities'      => $activities,
            'groups'          => $this->getGroups(),
            'types'           => $this->types,
            'statuses'        => $this->statuses,
            'typeLabels'      => $this->typeLabels,
            'typeColors'      => $this->typeColors,
            'statusConfig'    => $this->statusConfig,
            'totalActivities' => $stats->total,
            'activeCount'     => $stats->active_count,
            'homeworkCount'   => $stats->homework_count,
            'examCount'       => $stats->exam_count,
            'avgCoins'        => $stats->avg_coins,
            'sortField'       => $sortField,
            'sortDirection'   => $sortDirection,
        ]);
    }

    public function create(): View
    {
        return view('Activity.create', [
            'groups'   => $this->getGroups(),
            'types'    => $this->types,
            'statuses' => $this->statuses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules($request));

        $validated['created_by']  = Auth::id();
        $validated['is_required'] = $request->boolean('is_required');

        DB::beginTransaction();
        try {
            if ($path = $this->handleImageUpload($request)) {
                $validated['image'] = $path;
            }
            if ($path = $this->handleFileUpload($request)) {
                $validated['file'] = $path;
            }

            Activity::create($validated);
            DB::commit();

            return redirect()->route('activities.index')
                ->with('success', 'Faoliyat muvaffaqiyatli yaratildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(string $id): View
    {
        $activity = Activity::with([
            'group.course',
            'coinTransactions.student.user',
            'creator',
        ])->findOrFail($id);

        // Compute stats from the already-loaded relation — no extra queries
        $transactions  = $activity->coinTransactions;
        $participants  = $transactions->count();
        $totalCoins    = $transactions->sum('coins');
        $avgCoins      = $participants > 0 ? round($transactions->avg('coins'), 1) : 0;
        $completionPct = ($activity->max_coins > 0 && $participants > 0)
            ? round(($avgCoins / $activity->max_coins) * 100)
            : 0;

        $tg = $this->typeGradients[$activity->type] ?? $this->typeGradients['other'];
        $sc = $this->statusConfig[$activity->status] ?? [
            'label' => ucfirst($activity->status),
            'dot'   => 'bg-gray-400',
            'class' => 'bg-gray-100 text-gray-700',
        ];

        return view('Activity.show', [
            'activity'      => $activity,
            // display maps
            'typeLabels'    => $this->typeLabels,
            'typeIcons'     => $this->typeIcons,
            'typeGradients' => $this->typeGradients,
            'statusConfig'  => $this->statusConfig,
            // resolved for this activity
            'tg'            => $tg,
            'sc'            => $sc,
            // computed stats
            'participants'  => $participants,
            'totalCoins'    => $totalCoins,
            'avgCoins'      => $avgCoins,
            'completionPct' => $completionPct,
        ]);
    }

    public function edit(string $id): View
    {
        $activity = Activity::findOrFail($id);

        return view('Activity.edit', [
            'activity' => $activity,
            'groups'   => $this->getGroups(),
            'types'    => $this->types,
            'statuses' => $this->statuses,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $activity  = Activity::findOrFail($id);
        $validated = $request->validate($this->validationRules($request));

        $validated['is_required'] = $request->boolean('is_required');

        DB::beginTransaction();
        try {
            if ($path = $this->handleImageUpload($request, $activity->image)) {
                $validated['image'] = $path;
            }
            if ($path = $this->handleFileUpload($request, $activity->file)) {
                $validated['file'] = $path;
            }

            // Explicit removal via checkbox
            if ($request->boolean('remove_image') && $activity->image) {
                Storage::disk('public')->delete($activity->image);
                $validated['image'] = null;
            }
            if ($request->boolean('remove_file') && $activity->file) {
                Storage::disk('public')->delete($activity->file);
                $validated['file'] = null;
            }

            $activity->update($validated);
            DB::commit();

            return redirect()->route('activities.show', $activity->id)
                ->with('success', 'Faoliyat muvaffaqiyatli yangilandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        $activity = Activity::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($activity->image) Storage::disk('public')->delete($activity->image);
            if ($activity->file)  Storage::disk('public')->delete($activity->file);

            $activity->delete(); // SoftDelete
            DB::commit();

            return redirect()->route('activities.index')
                ->with('success', "Faoliyat muvaffaqiyatli o'chirildi!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}
