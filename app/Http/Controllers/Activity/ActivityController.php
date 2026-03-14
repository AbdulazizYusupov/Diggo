<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $typeLabels = [
            'homework'    => 'Uyga vazifa',
            'exam'        => 'Imtihon',
            'competition' => 'Musobaqa',
            'project'     => 'Loyiha',
            'other'       => 'Boshqa',
        ];

        $typeColors = [
            'exam'        => 'red',
            'homework'    => 'blue',
            'competition' => 'purple',
            'project'     => 'green',
            'other'       => 'gray',
        ];

        $statusConfig = [
            'draft'     => ['label' => 'Qoralama',    'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700/40 dark:text-gray-300'],
            'active'    => ['label' => 'Faol',        'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'],
            'completed' => ['label' => 'Tugallangan', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'],
            'cancelled' => ['label' => 'Bekor',       'class' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400'],
        ];

        // ── Allowed sort fields (whitelist — prevents SQL injection) ──────────
        $allowedSorts = ['name', 'date', 'type', 'status', 'max_coins', 'created_at'];
        $sortField     = in_array($request->get('sort'), $allowedSorts)
            ? $request->get('sort')
            : 'date';
        $sortDirection = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        // ── Filtered + sorted + paginated query ───────────────────────────────
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

        // ── Stats — ONE query instead of 5 separate count() calls ─────────────
        $stats = Activity::selectRaw('
        COUNT(*)                                            AS total,
        SUM(CASE WHEN status = "active"   THEN 1 ELSE 0 END) AS active_count,
        SUM(CASE WHEN type   = "homework" THEN 1 ELSE 0 END) AS homework_count,
        SUM(CASE WHEN type   = "exam"     THEN 1 ELSE 0 END) AS exam_count,
        COALESCE(AVG(max_coins), 0)                          AS avg_coins
    ')->first();

        // ── Groups for filter dropdown ─────────────────────────────────────────
        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        return view('Activity.index', [
            'activities'      => $activities,
            'groups'          => $groups,
            'types'           => array_keys($typeLabels),
            'statuses'        => array_keys($statusConfig),
            'typeLabels'      => $typeLabels,
            'typeColors'      => $typeColors,
            'statusConfig'    => $statusConfig,
            'totalActivities' => $stats->total,
            'activeCount'     => $stats->active_count,
            'homeworkCount'   => $stats->homework_count,
            'examCount'       => $stats->exam_count,
            'avgCoins'        => $stats->avg_coins,
            'sortField'       => $sortField,
            'sortDirection'   => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        $types    = ['homework', 'exam', 'competition', 'project', 'other'];
        $statuses = ['draft', 'active', 'completed', 'cancelled'];

        return view('Activity.create', compact('groups', 'types', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:homework,exam,competition,project,other',
            'status'      => 'required|in:draft,active,completed,cancelled',
            'group_id'    => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,txt|max:10240',
            'date'        => 'required|date',
            'start_time' => [
                'date_format:H:i',
                Rule::requiredIf(fn() => in_array($request->type, ['exam', 'competition'])),
                'nullable',
            ],
            'due_time' => [
                'date_format:H:i',
                Rule::requiredIf(fn() => in_array($request->type, ['exam', 'competition'])),
                'nullable',
                'after:start_time',
            ],
            'max_coins'   => 'required|integer|min:0|max:1000',
            'is_required' => 'boolean',
        ]);

        $validated['created_by']  = Auth::id();
        $validated['is_required'] = $request->boolean('is_required');

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('activities/images', 'public');
            }

            // Handle file upload
            if ($request->hasFile('file')) {
                $validated['file'] = $request->file('file')->store('activities/files', 'public');
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activity = Activity::with(['group.course', 'coinTransactions.student.user', 'creator'])
            ->findOrFail($id);

        return view('Activity.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activity = Activity::findOrFail($id);

        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        $types    = ['homework', 'exam', 'competition', 'project', 'other'];
        $statuses = ['draft', 'active', 'completed', 'cancelled'];

        return view('Activity.edit', compact('activity', 'groups', 'types', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:homework,exam,competition,project,other',
            'status'      => 'required|in:draft,active,completed,cancelled',
            'group_id'    => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,txt|max:10240',
            'date'        => 'required|date',
            'start_time' => [
                'date_format:H:i',
                Rule::requiredIf(fn() => in_array($request->type, ['exam', 'competition'])),
                'nullable',
            ],
            'due_time' => [
                'date_format:H:i',
                Rule::requiredIf(fn() => in_array($request->type, ['exam', 'competition'])),
                'nullable',
                'after:start_time',
            ],
            'max_coins'   => 'required|integer|min:0|max:1000',
            'is_required' => 'boolean',
        ]);

        $validated['is_required'] = $request->boolean('is_required');

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($activity->image) {
                    Storage::disk('public')->delete($activity->image);
                }
                $validated['image'] = $request->file('image')->store('activities/images', 'public');
            }

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if it exists
                if ($activity->file) {
                    Storage::disk('public')->delete($activity->file);
                }
                $validated['file'] = $request->file('file')->store('activities/files', 'public');
            }

            // Handle image removal
            if ($request->boolean('remove_image') && $activity->image) {
                Storage::disk('public')->delete($activity->image);
                $validated['image'] = null;
            }

            // Handle file removal
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::findOrFail($id);

        DB::beginTransaction();
        try {
            // Clean up uploaded files on delete
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            if ($activity->file) {
                Storage::disk('public')->delete($activity->file);
            }

            $activity->delete(); // SoftDelete

            DB::commit();

            return redirect()->route('activities.index')
                ->with('success', 'Faoliyat muvaffaqiyatli o\'chirildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }
}
