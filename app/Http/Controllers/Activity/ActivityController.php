<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['group.course']);

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

        // Filter by group
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
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
        $query->orderBy($sortField, $sortDirection);

        $activities = $query->paginate(15)->withQueryString();

        // Statistics
        $totalActivities = Activity::count();
        $homeworkCount = Activity::where('type', 'homework')->count();
        $examCount = Activity::where('type', 'exam')->count();
        $avgCoins = Activity::avg('max_coins');

        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        $types = ['homework', 'exam', 'competition', 'project', 'other'];

        return view('Activity.index', compact(
            'activities',
            'groups',
            'types',
            'totalActivities',
            'homeworkCount',
            'examCount',
            'avgCoins'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:homework,exam,competition,project,other',
            'group_id' => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'max_coins' => 'required|integer|min:0|max:1000',
        ]);

        DB::beginTransaction();
        try {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:homework,exam,competition,project,other',
            'group_id' => 'nullable|exists:groups,id',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'max_coins' => 'required|integer|min:0|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $activity->update($validated);

            DB::commit();

            return redirect()->route('activities.index')
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
            $activity->delete();

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
