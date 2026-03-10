<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $this->merge([
                'amount' => str_replace(' ', '', $this->amount),
            ]);
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
            'student_id'         => 'required|exists:students,id',
            'group_id'           => 'required|exists:groups,id',
            'amount'             => 'required|numeric|min:1',
            'discount'           => 'required|numeric|min:0|max:100',
            'payment_type'       => 'required|in:cash,card,transfer,other',
            'payment_month'      => 'required|date',
            'is_installment'     => 'boolean',
            'installment_number' => 'nullable|integer|min:1',
            'total_installments' => 'nullable|integer|min:1',
            'notes'              => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id'         => __('validation.attributes.student_id'),
            'group_id'           => __('validation.attributes.group_id'),
            'amount'             => __('validation.attributes.amount'),
            'discount'           => __('validation.attributes.discount'),
            'payment_type'       => __('validation.attributes.payment_type'),
            'payment_month'      => __('validation.attributes.payment_month'),
            'is_installment'     => __('validation.attributes.is_installment'),
            'installment_number' => __('validation.attributes.installment_number'),
            'total_installments' => __('validation.attributes.total_installments'),
            'notes'              => __('validation.attributes.notes'),
        ];
    }
    public function messages(): array
    {
        return [
            'student_id.required'         => __('validation.custom.student_id.required'),
            'student_id.exists'           => __('validation.custom.student_id.exists'),
            'group_id.required'           => __('validation.custom.group_id.required'),
            'group_id.exists'             => __('validation.custom.group_id.exists'),
            'amount.required'             => __('validation.custom.amount.required'),
            'amount.numeric'              => __('validation.custom.amount.numeric'),
            'amount.min'                  => __('validation.custom.amount.min'),
            'discount.required'           => __('validation.custom.discount.required'),
            'discount.numeric'            => __('validation.custom.discount.numeric'),
            'discount.min'                => __('validation.custom.discount.min'),
            'discount.max'                => __('validation.custom.discount.max'),
            'payment_type.required'       => __('validation.custom.payment_type.required'),
            'payment_type.in'             => __('validation.custom.payment_type.in'),
            'payment_month.required'      => __('validation.custom.payment_month.required'),
            'payment_month.date'          => __('validation.custom.payment_month.date'),
            'is_installment.boolean'      => __('validation.custom.is_installment.boolean'),
            'installment_number.integer'  => __('validation.custom.installment_number.integer'),
            'installment_number.min'      => __('validation.custom.installment_number.min'),
            'total_installments.integer'  => __('validation.custom.total_installments.integer'),
            'total_installments.min'      => __('validation.custom.total_installments.min'),
            'notes.string'                => __('validation.custom.notes.string'),
            'notes.max'                   => __('validation.custom.notes.max'),
        ];
    }
}
