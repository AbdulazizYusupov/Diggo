<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }
    protected function prepareForValidation(): void
    {
        // Normalize nested attendance entries
        if ($this->has('attendances') && is_array($this->attendances)) {
            $normalized = [];

            foreach ($this->attendances as $studentRows) {
                if (!is_array($studentRows)) {
                    continue;
                }

                foreach ($studentRows as $entry) {
                    if (!is_array($entry)) {
                        continue;
                    }

                    // Trim string fields
                    if (isset($entry['notes'])) {
                        $entry['notes'] = trim($entry['notes']);
                    }

                    // Cast numeric fields to proper types
                    if (isset($entry['student_id'])) {
                        $entry['student_id'] = (int) $entry['student_id'];
                    }
                    if (isset($entry['lesson_id'])) {
                        $entry['lesson_id'] = (int) $entry['lesson_id'];
                    }
                    if (isset($entry['late_minutes'])) {
                        $entry['late_minutes'] = (int) $entry['late_minutes'];
                    }
                    if (isset($entry['homework_score'])) {
                        $entry['homework_score'] = (int) $entry['homework_score'];
                    }
                    if (isset($entry['activity_score'])) {
                        $entry['activity_score'] = (int) $entry['activity_score'];
                    }

                    $normalized[][] = $entry;
                }
            }

            $this->merge(['attendances' => $normalized]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'group_id'                          => 'required|integer|exists:groups,id',
            'attendances'                        => 'required|array|min:1',
            'attendances.*'                      => 'array',
            'attendances.*.*'                    => 'array',
            'attendances.*.*'                    => 'array',
            'attendances.*.*.student_id'         => 'nullable|integer|exists:students,id',
            'attendances.*.*.lesson_id'          => 'nullable|integer|exists:lessons,id',
            'attendances.*.*.status'             => 'nullable|string|in:present,late,absent,excused',
            'attendances.*.*.late_minutes'       => 'nullable|integer|min:0|max:120',
            'attendances.*.*.homework_score'     => 'nullable|integer|min:0|max:3',
            'attendances.*.*.activity_score'     => 'nullable|integer|min:0|max:3',
            'attendances.*.*.notes'              => 'nullable|string|max:500',
        ];
    }
    public function attributes(): array
    {
        return [
            'group_id'                       => __('validation.attributes.group_id'),
            'attendances'                    => __('validation.attributes.attendances'),
            'attendances.*.*.student_id'     => __('validation.attributes.student_id'),
            'attendances.*.*.lesson_id'      => __('validation.attributes.lesson_id'),
            'attendances.*.*.status'         => __('validation.attributes.status'),
            'attendances.*.*.late_minutes'   => __('validation.attributes.late_minutes'),
            'attendances.*.*.homework_score' => __('validation.attributes.homework_score'),
            'attendances.*.*.activity_score' => __('validation.attributes.activity_score'),
            'attendances.*.*.notes'          => __('validation.attributes.notes'),
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $groupId = $this->input('group_id');
            $attendances = $this->input('attendances', []);

            // Check if user has permission for this group
            $user = Auth::user();
            $group = \App\Models\Group::find($groupId);

            if ($group && !$user->hasRole('admin')) {
                $isTeacher = $group->teachers()->where('user_id', $user->id)->exists();
                if (!$isTeacher) {
                    $validator->errors()->add('group_id', 'Siz bu guruh uchun davomat belgilay olmaysiz.');
                }
            }

            // Business rule validations
            foreach ($attendances as $studentIndex => $studentRows) {
                foreach ($studentRows as $lessonIndex => $entry) {
                    $status = $entry['status'] ?? null;
                    $lateMinutes = $entry['late_minutes'] ?? 0;
                    $homeworkScore = $entry['homework_score'] ?? 0;
                    $activityScore = $entry['activity_score'] ?? 0;

                    // Late minutes faqat late status uchun
                    if ($status !== 'late' && $lateMinutes > 0) {
                        $validator->errors()->add(
                            "attendances.{$studentIndex}.{$lessonIndex}.late_minutes",
                            'Kechikish vaqti faqat "Kechikdi" status uchun kiritilishi mumkin.'
                        );
                    }

                    // Homework va activity score faqat present yoki late uchun
                    if (!in_array($status, ['present', 'late'])) {
                        if ($homeworkScore > 0) {
                            $validator->errors()->add(
                                "attendances.{$studentIndex}.{$lessonIndex}.homework_score",
                                'Uy vazifa bali faqat "Keldi" yoki "Kechikdi" status uchun berilishi mumkin.'
                            );
                        }
                        if ($activityScore > 0) {
                            $validator->errors()->add(
                                "attendances.{$studentIndex}.{$lessonIndex}.activity_score",
                                'Faollik bali faqat "Keldi" yoki "Kechikdi" status uchun berilishi mumkin.'
                            );
                        }
                    }

                    // Late minutes validation
                    if ($status === 'late') {
                        if ($lateMinutes <= 0) {
                            $validator->errors()->add(
                                "attendances.{$studentIndex}.{$lessonIndex}.late_minutes",
                                'Kechikish vaqti kiritilishi shart.'
                            );
                        } elseif ($lateMinutes >= 30) {
                            $validator->errors()->add(
                                "attendances.{$studentIndex}.{$lessonIndex}.late_minutes",
                                '30 daqiqadan ko\'p kechikish "Kelmadi" hisoblanadi.'
                            );
                        }
                    }
                }
            }
        });
    }
}
