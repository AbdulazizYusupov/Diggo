<?php

namespace App\Http\Controllers\GroupTeacher;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request, Group $group)
    {
        $group->load(['course', 'teachers.user']);
        // Guruhda bo'lmagan o'qituvchilar
        $availableTeachers = Teacher::with('user')
            ->whereDoesntHave('groups', function ($query) use ($group) {
                $query->where('group_id', $group->id);
            })
            ->get();

        return view('Group.Teacher.index', compact('group', 'availableTeachers'));
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
    public function store(Request $request, Group $group)
    {
        // Hozirgi asosiy o'qituvchini topamiz
        $currentPrimary = GroupTeacher::where('group_id', $group->id)
            ->where('is_primary', true)
            ->first();

        $request->validate([
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'exists:teachers,id',
            'primary_teacher_id' => $currentPrimary
                ? 'nullable|exists:teachers,id'
                : 'required|exists:teachers,id',
        ]);

        DB::transaction(function () use ($request, $group, $currentPrimary) {
            // 1. Agar yangi primary tanlangan bo'lsa, eskisini bekor qilamiz
            if ($request->filled('primary_teacher_id')) {
                GroupTeacher::where('group_id', $group->id)
                    ->update(['is_primary' => false]);

                $targetPrimaryId = $request->primary_teacher_id;
            } else {
                // Agar yangi primary tanlanmagan bo'lsa, mavjudini saqlab qolamiz
                $targetPrimaryId = $currentPrimary->teacher_id;
            }

            // 2. O'qituvchilarni bog'laymiz
            foreach ($request->teacher_ids as $teacherId) {
                GroupTeacher::updateOrCreate(
                    ['group_id' => $group->id, 'teacher_id' => $teacherId],
                    [
                        'is_primary' => $targetPrimaryId == $teacherId,
                        'assigned_at' => now(),
                    ]
                );
            }

            // 3. Darslarni yangilaymiz (Hech qachon null bo'lmasligini ta'minlaydi)
            Lesson::where('group_id', $group->id)
                ->where(function ($query) use ($targetPrimaryId) {
                    $query->whereNull('teacher_id')
                        ->orWhere('teacher_id', '!=', $targetPrimaryId);
                })
                ->update(['teacher_id' => $targetPrimaryId]);
        });

        return back()->with('success', 'O‘qituvchilar muvaffaqiyatli qo‘shildi');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, Teacher $teacher)
    {
        $relation = GroupTeacher::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$relation) {
            return back()->with('error', 'O\'qituvchi ushbu guruhda topilmadi.');
        }

        if ($relation->is_primary) {
            return back()->with('error', 'Bu asosiy o\'qituvchi! Avval boshqasini asosiy qilib tayinlang.');
        }
        $relation->delete();

        return back()->with('success', 'O\'qituvchi guruhdan muvaffaqiyatli chiqarildi.');
    }
    public function makePrimaryTeacher(Group $group, Teacher $teacher)
    {
        // Avval barcha o'qituvchilarni oddiy qilish
        $group->teachers()->updateExistingPivot(
            $group->teachers->pluck('id')->toArray(),
            ['is_primary' => false]
        );
        // Tanlangan o'qituvchini asosiy qilish
        $group->teachers()->updateExistingPivot($teacher->id, [
            'is_primary' => true
        ]);

        return back()->with('success', 'Asosiy o\'qituvchi o\'zgartirildi');
    }
}
