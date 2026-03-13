<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['group.course', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by group
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        // Filter by is_required
        if ($request->filled('is_required')) {
            $query->where('is_required', (bool) $request->is_required);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Sort
        $sortField = $request->get('sort', 'date');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['name', 'date', 'type', 'status', 'max_coins', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'date';
        }
        $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');

        $activities = $query->paginate(15)->withQueryString();

        // Statistics
        $totalActivities = Activity::count();
        $homeworkCount   = Activity::where('type', 'homework')->count();
        $examCount       = Activity::where('type', 'exam')->count();
        $avgCoins        = Activity::avg('max_coins');
        $activeCount     = Activity::where('status', 'active')->count();

        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        $types    = ['homework', 'exam', 'competition', 'project', 'other'];
        $statuses = ['draft', 'active', 'completed', 'cancelled'];

        return view('Activity.index', compact(
            'activities',
            'groups',
            'types',
            'statuses',
            'totalActivities',
            'homeworkCount',
            'examCount',
            'avgCoins',
            'activeCount'
        ));
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
            'due_time'    => 'nullable|date_format:H:i',
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
            'due_time'    => 'nullable|date_format:H:i',
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