<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosApplyCouponRequest extends FormRequest
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
            'coupon_code' => 'required|string',
            'name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_code.required' => __('The coupon id field is required.'),
            'name.required' => __('The name field is required.'),
        ];
    }
}
