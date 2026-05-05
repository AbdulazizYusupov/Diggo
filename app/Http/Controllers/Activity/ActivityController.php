<?php

namespace App\Http\Controllers\Activity;

use App\Enums\ActivityStatus;
use App\Enums\ActivityType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\StoreActivityRequest;
use App\Http\Requests\Activity\UpdateActivityRequest;
use App\Models\Activity;
use App\Services\ActivityService\ActivityService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private readonly ActivityService $activityService,
    ) {}

    // ═══════════════════════════════════════════════════════════════
    //  INDEX
    // ═══════════════════════════════════════════════════════════════

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Activity::class);

        $activities = $this->activityService->getPaginatedActivities($request);
        $stats      = $this->activityService->getStats();

        return view('Activity.index', [
            'activities'      => $activities,
            'groups'          => $this->activityService->getActiveGroups(),
            'types'           => ActivityType::cases(),
            'statuses'        => ActivityStatus::cases(),
            // Stats
            'totalActivities' => $stats->total,
            'activeCount'     => $stats->active_count,
            'homeworkCount'   => $stats->homework_count,
            'examCount'       => $stats->exam_count,
            'avgCoins'        => $stats->avg_coins,
            // Sort state
            'sortField'       => $request->get('sort', 'date'),
            'sortDirection'   => $request->get('direction', 'desc'),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    //  CREATE / STORE
    // ═══════════════════════════════════════════════════════════════

    public function create(): View
    {
        $this->authorize('create', Activity::class);

        return view('Activity.create', [
            'groups'   => $this->activityService->getActiveGroups(),
            'types'    => ActivityType::cases(),
            'statuses' => ActivityStatus::cases(),
        ]);
    }

    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $this->authorize('create', Activity::class);

        try {
            $this->activityService->create(
                $request->validated(),
                $request,
                Auth::id(),
            );

            return redirect()->route('activities.index')
                ->with('success', 'Faoliyat muvaffaqiyatli yaratildi!');
        } catch (\Exception $e) {
            report($e); // Log to Laravel log system

            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi. Iltimos qayta urinib ko\'ring.')
                ->withInput();
        }
    }

    // ═══════════════════════════════════════════════════════════════
    //  SHOW
    // ═══════════════════════════════════════════════════════════════

    public function show(int $id): View
    {
        $activity = $this->activityService->getActivityWithDetails($id);

        $this->authorize('view', $activity);

        $stats = $this->activityService->computeActivityStats($activity);
        $type   = $activity->type;
        $status = $activity->status;
        return view('Activity.show', [
            'activity'      => $activity,
            'type'          => $type,
            'status'        => $status,
            // Computed stats
            'participants'  => $stats['participants'],
            'totalCoins'    => $stats['totalCoins'],
            'avgCoins'      => $stats['avgCoins'],
            'completionPct' => $stats['completionPct'],
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    //  EDIT / UPDATE
    // ═══════════════════════════════════════════════════════════════

    public function edit(int $id): View
    {
        $activity = Activity::findOrFail($id);

        $this->authorize('update', $activity);

        return view('Activity.edit', [
            'activity' => $activity,
            'groups'   => $this->activityService->getActiveGroups(),
            'types'    => ActivityType::cases(),
            'statuses' => ActivityStatus::cases(),
        ]);
    }

    public function update(UpdateActivityRequest $request, int $id): RedirectResponse
    {
        $activity = Activity::findOrFail($id);

        $this->authorize('update', $activity);

        try {
            $this->activityService->update(
                $request->validated(),
                $request,
                $activity,
            );

            return redirect()->route('activities.show', $activity->id)
                ->with('success', 'Faoliyat muvaffaqiyatli yangilandi!');
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi. Iltimos qayta urinib ko\'ring.')
                ->withInput();
        }
    }

    // ═══════════════════════════════════════════════════════════════
    //  DESTROY
    // ═══════════════════════════════════════════════════════════════

    public function destroy(int $id): RedirectResponse
    {
        $activity = Activity::findOrFail($id);

        $this->authorize('delete', $activity);

        try {
            $this->activityService->delete($activity);

            return redirect()->route('activities.index')
                ->with('success', "Faoliyat muvaffaqiyatli o'chirildi!");
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Xatolik yuz berdi. Iltimos qayta urinib ko\'ring.');
        }
    }
}
