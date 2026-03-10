<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'group_id'      => 'required|exists:groups,id',
            'amount'        => 'required|numeric|min:1',
            'discount'      => 'required|numeric|min:0|max:100',
            'payment_type'  => 'required|in:cash,card,transfer,other',
            'payment_month' => 'required|date',
            'notes'         => 'nullable|string|max:1000',
        ];
    }
    public function messages(): array
    {
        return [
            'group_id.required'      => __('validation.custom.group_id.required'),
            'group_id.exists'        => __('validation.custom.group_id.exists'),
            'amount.required'        => __('validation.custom.amount.required'),
            'amount.numeric'         => __('validation.custom.amount.numeric'),
            'amount.min'             => __('validation.custom.amount.min'),
            'discount.required'      => __('validation.custom.discount.required'),
            'discount.numeric'       => __('validation.custom.discount.numeric'),
            'discount.min'           => __('validation.custom.discount.min'),
            'discount.max'           => __('validation.custom.discount.max'),
            'payment_type.required'  => __('validation.custom.payment_type.required'),
            'payment_type.in'        => __('validation.custom.payment_type.in'),
            'payment_month.required' => __('validation.custom.payment_month.required'),
            'payment_month.date'     => __('validation.custom.payment_month.date'),
            'notes.string'           => __('validation.custom.notes.string'),
            'notes.max'              => __('validation.custom.notes.max'),
        ];
    }
}
