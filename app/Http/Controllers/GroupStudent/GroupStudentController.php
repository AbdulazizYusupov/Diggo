<?php

namespace App\Http\Controllers\GroupStudent;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;

class GroupStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Group $group)
    {
        $group->load(['course', 'activeStudents.user']);

        $availableStudents = Student::with('user')
            ->whereDoesntHave('groups', function ($query) use ($group) {
                $query->where('group_id', $group->id)
                    ->where('status', 'active');
            })
            ->get();
        return view('Group.Student.index', compact('group', 'availableStudents'));
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
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        // Sig'im tekshirish
        $currentCount = $group->activeStudents->count();
        $newCount = count($request->student_ids);

        if (($currentCount + $newCount) > $group->capacity) {
            return back()->with('error', 'Guruh sig\'imi to\'lgan! Maksimal ' . $group->capacity . ' ta talaba');
        }

        foreach ($request->student_ids as $studentId) {
            $group->students()->attach($studentId, [
                'joined_at' => now(),
                'status' => 'active'
            ]);
        }
        return back()->with('success', 'Talabalar muvaffaqiyatli qo\'shildi');
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
    public function destroy(Group $group, Student $student)
    {
        $group->students()->updateExistingPivot($student->id, [
            'status' => 'dropped',
            'left_at' => now()
        ]);

        return back()->with('success', 'Talaba guruhdan chiqarildi');
    }
}
