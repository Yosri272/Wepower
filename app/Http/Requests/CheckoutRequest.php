<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'shop_ids' => 'nullable|array',
            'shop_ids.*' => 'nullable|exists:shops,id',
            'coupon_code' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        $request = request();
        if ($request->is('api/*')) {
            $lan = $request->header('accept-language') ?? 'en';
            app()->setLocale($lan);
        }

        return [
            'shop_ids.*.array' => __('The shop ids must be an array.'),
            'shop_ids.*.exists' => __('The selected shop ids are invalid.'),
            'coupon_code.max' => __('The coupon code may not be greater than 50 characters.'),

        ];
    }
}
