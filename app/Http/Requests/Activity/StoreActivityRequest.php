<?php

namespace App\Http\Requests\Activity;

use App\Enums\ActivityStatus;
use App\Enums\ActivityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreActivityRequest extends FormRequest
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
        $type = ActivityType::tryFrom($this->input('type'));

        return [
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', new Enum(ActivityType::class)],
            'status'      => ['required', new Enum(ActivityStatus::class)],
            'group_id'    => ['nullable', 'exists:groups,id'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'file'        => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip,txt', 'max:10240'],
            'date'        => ['required', 'date'],
            'start_time'  => [
                'nullable',
                'date_format:H:i',
                Rule::requiredIf(fn() => $type?->requiresTime()),
            ],
            'due_time'    => [
                'nullable',
                'date_format:H:i',
                'after:start_time',
                Rule::requiredIf(fn() => $type?->requiresTime()),
            ],
            'max_coins'   => ['required', 'integer', 'min:0', 'max:1000'],
            'is_required' => ['boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'       => 'Faoliyat nomi kiritilishi shart.',
            'type.required'       => 'Faoliyat turi tanlanishi shart.',
            'status.required'     => 'Holat tanlanishi shart.',
            'date.required'       => 'Sana kiritilishi shart.',
            'start_time.required' => 'Imtihon/musobaqa uchun boshlanish vaqti shart.',
            'due_time.required'   => 'Imtihon/musobaqa uchun tugash vaqti shart.',
            'due_time.after'      => 'Tugash vaqti boshlanish vaqtidan keyin bo\'lishi kerak.',
            'max_coins.max'       => 'Maksimal coin 1000 dan oshmasligi kerak.',
            'image.max'           => 'Rasm hajmi 2MB dan oshmasligi kerak.',
            'file.max'            => 'Fayl hajmi 10MB dan oshmasligi kerak.',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->boolean('is_required'),
        ]);
    }
}
