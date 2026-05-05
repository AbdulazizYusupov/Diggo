<?php

namespace App\Services\ActivityService;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActivityService
{
    private const IMAGE_DISK      = 'public';
    private const IMAGE_PATH      = 'activities/images';
    private const FILE_PATH       = 'activities/files';
    private const ALLOWED_SORTS   = ['name', 'date', 'type', 'status', 'max_coins', 'created_at'];
    private const PER_PAGE        = 15;

    // ═══════════════════════════════════════════════════════════════
    //  QUERY METHODS
    // ═══════════════════════════════════════════════════════════════

    public function getPaginatedActivities(Request $request): LengthAwarePaginator
    {
        $sortField     = in_array($request->get('sort'), self::ALLOWED_SORTS)
            ? $request->get('sort')
            : 'date';
        $sortDirection = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        return Activity::with(['group.course', 'creator'])
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
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    public function getStats(): object
    {
        return Activity::selectRaw('
            COUNT(*)                                               AS total,
            SUM(CASE WHEN status = "active"   THEN 1 ELSE 0 END)  AS active_count,
            SUM(CASE WHEN type   = "homework" THEN 1 ELSE 0 END)  AS homework_count,
            SUM(CASE WHEN type   = "exam"     THEN 1 ELSE 0 END)  AS exam_count,
            COALESCE(AVG(max_coins), 0)                            AS avg_coins
        ')->first();
    }

    public function getActivityWithDetails(int $id): Activity
    {
        return Activity::with([
            'group.course',
            'coinTransactions.student.user',
            'creator',
        ])->findOrFail($id);
    }

    public function computeActivityStats(Activity $activity): array
    {
        $transactions = $activity->coinTransactions;
        $participants = $transactions->count();
        $avgCoins     = $participants > 0 ? round($transactions->avg('coins'), 1) : 0;

        return [
            'participants'  => $participants,
            'totalCoins'    => $transactions->sum('coins'),
            'avgCoins'      => $avgCoins,
            'completionPct' => ($activity->max_coins > 0 && $participants > 0)
                ? round(($avgCoins / $activity->max_coins) * 100)
                : 0,
        ];
    }

    public function getActiveGroups()
    {
        return Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();
    }

    // ═══════════════════════════════════════════════════════════════
    //  CRUD METHODS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Yangi activity yaratish.
     * Fayllar DB commit'dan KEYIN saqlanmaydi — avval DB, keyin storage.
     */
    public function create(array $validated, Request $request, int $createdBy): Activity
    {
        $validated['created_by']  = $createdBy;
        $validated['is_required'] = $validated['is_required'] ?? false;

        return DB::transaction(function () use ($validated, $request) {
            $activity = Activity::create($validated);

            // Fayllarni DB yozilgandan keyin saqlaymiz
            $this->saveUploads($request, $activity);

            return $activity;
        });
    }

    /**
     * Mavjud activity'ni yangilash.
     */
    public function update(array $validated, Request $request, Activity $activity): Activity
    {
        return DB::transaction(function () use ($validated, $request, $activity) {
            // Avval DB'ni yangilaymiz
            $activity->update($validated);

            // Yangi fayl yuklangan bo'lsa eski'sini almashtiramiz
            $this->replaceUploads($request, $activity);

            // Explicit olib tashlash so'ralgan bo'lsa
            $this->removeUploadsIfRequested($request, $activity);

            return $activity->fresh();
        });
    }

    /**
     * Activity'ni soft-delete qilish.
     * Fayllar DB'dan o'chirilgandan keyin o'chiriladi.
     */
    public function delete(Activity $activity): void
    {
        DB::transaction(function () use ($activity) {
            $imagePath = $activity->image;
            $filePath  = $activity->file;

            $activity->delete(); // Soft delete

            // DB o'chirildi — endi storage'dan ham olib tashlaymiz
            if ($imagePath) {
                Storage::disk(self::IMAGE_DISK)->delete($imagePath);
            }
            if ($filePath) {
                Storage::disk(self::IMAGE_DISK)->delete($filePath);
            }
        });
    }

    // ═══════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════════════

    private function saveUploads(Request $request, Activity $activity): void
    {
        $updates = [];

        if ($request->hasFile('image')) {
            $updates['image'] = $request->file('image')
                ->store(self::IMAGE_PATH, self::IMAGE_DISK);
        }

        if ($request->hasFile('file')) {
            $updates['file'] = $request->file('file')
                ->store(self::FILE_PATH, self::IMAGE_DISK);
        }

        if (!empty($updates)) {
            $activity->update($updates);
        }
    }

    private function replaceUploads(Request $request, Activity $activity): void
    {
        $updates = [];

        if ($request->hasFile('image')) {
            $this->deleteFromStorage($activity->image);
            $updates['image'] = $request->file('image')
                ->store(self::IMAGE_PATH, self::IMAGE_DISK);
        }

        if ($request->hasFile('file')) {
            $this->deleteFromStorage($activity->file);
            $updates['file'] = $request->file('file')
                ->store(self::FILE_PATH, self::IMAGE_DISK);
        }

        if (!empty($updates)) {
            $activity->update($updates);
        }
    }

    private function removeUploadsIfRequested(Request $request, Activity $activity): void
    {
        $updates = [];

        if ($request->boolean('remove_image') && $activity->image) {
            $this->deleteFromStorage($activity->image);
            $updates['image'] = null;
        }

        if ($request->boolean('remove_file') && $activity->file) {
            $this->deleteFromStorage($activity->file);
            $updates['file'] = null;
        }

        if (!empty($updates)) {
            $activity->update($updates);
        }
    }

    private function deleteFromStorage(?string $path): void
    {
        if ($path && Storage::disk(self::IMAGE_DISK)->exists($path)) {
            Storage::disk(self::IMAGE_DISK)->delete($path);
        }
    }
}
