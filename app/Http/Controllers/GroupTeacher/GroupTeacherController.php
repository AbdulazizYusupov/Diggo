<?php

namespace App\Http\Controllers\GroupTeacher;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;

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
        $request->validate([
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'exists:teachers,id',
            'primary_teacher_id' => 'required|exists:teachers,id',
        ]);

        // 1. Avval eski primarylarni o‘chiramiz
        GroupTeacher::where('group_id', $group->id)
            ->update(['is_primary' => false]);

        // 2. Teacherlarni bittalab qo‘shamiz
        foreach ($request->teacher_ids as $teacherId) {

            GroupTeacher::updateOrCreate(
                [
                    'group_id'   => $group->id,
                    'teacher_id' => $teacherId,
                ],
                [
                    'is_primary' => $teacherId == $request->primary_teacher_id,
                    'assigned_at' => now(),
                ]
            );
        }

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
