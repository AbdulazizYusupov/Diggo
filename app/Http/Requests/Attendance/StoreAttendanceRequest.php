<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'group_id'                              => ['required', 'integer', 'exists:groups,id'],
            'attendances'                           => ['required', 'array'],
            // Each top-level key is a student_id; each value is an associative
            // array keyed by date string.
            'attendances.*'                         => ['array'],
            'attendances.*.*'                       => ['array'],
            'attendances.*.*.status'                => ['nullable', 'string', 'in:present,absent,late,excused'],
            'attendances.*.*.late_minutes'          => ['nullable', 'integer', 'min:0', 'max:120'],
            'attendances.*.*.homework_score'        => ['nullable', 'integer', 'min:0', 'max:3'],
            'attendances.*.*.activity_score'        => ['nullable', 'integer', 'min:0', 'max:3'],
            'attendances.*.*.notes'                 => ['nullable', 'string', 'max:500'],
        ];
    }
    public function messages(): array
    {
        return [
            'group_id.required' => 'Guruh tanlanishi shart.',
            'group_id.exists'   => 'Tanlangan guruh mavjud emas.',
            'attendances.required' => 'Davomat ma\'lumotlari kiritilishi shart.',
        ];
    }
}
