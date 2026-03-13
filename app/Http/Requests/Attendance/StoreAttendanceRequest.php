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
    public function messages(): array
    {
        return [
            // group_id
            'group_id.required'                       => __('validation.custom.group_id.required'),
            'group_id.integer'                        => __('validation.custom.group_id.integer'),
            'group_id.exists'                         => __('validation.custom.group_id.exists'),

            // attendances
            'attendances.required'                    => __('validation.custom.attendances.required'),
            'attendances.array'                       => __('validation.custom.attendances.array'),
            'attendances.min'                         => __('validation.custom.attendances.min'),

            // student_id
            'attendances.*.*.student_id.integer'      => __('validation.custom.student_id.integer'),
            'attendances.*.*.student_id.exists'       => __('validation.custom.student_id.exists'),

            // lesson_id
            'attendances.*.*.lesson_id.integer'       => __('validation.custom.lesson_id.integer'),
            'attendances.*.*.lesson_id.exists'        => __('validation.custom.lesson_id.exists'),

            // status
            'attendances.*.*.status.in'               => __('validation.custom.status.in'),

            // late_minutes
            'attendances.*.*.late_minutes.integer'    => __('validation.custom.late_minutes.integer'),
            'attendances.*.*.late_minutes.min'        => __('validation.custom.late_minutes.min'),
            'attendances.*.*.late_minutes.max'        => __('validation.custom.late_minutes.max'),

            // homework_score
            'attendances.*.*.homework_score.integer'  => __('validation.custom.homework_score.integer'),
            'attendances.*.*.homework_score.min'      => __('validation.custom.homework_score.min'),
            'attendances.*.*.homework_score.max'      => __('validation.custom.homework_score.max'),

            // activity_score
            'attendances.*.*.activity_score.integer'  => __('validation.custom.activity_score.integer'),
            'attendances.*.*.activity_score.min'      => __('validation.custom.activity_score.min'),
            'attendances.*.*.activity_score.max'      => __('validation.custom.activity_score.max'),

            // notes
            'attendances.*.*.notes.string'            => __('validation.custom.notes.string'),
            'attendances.*.*.notes.max'               => __('validation.custom.notes.max'),
        ];
    }
}
