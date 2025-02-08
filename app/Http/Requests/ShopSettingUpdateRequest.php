<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopSettingUpdateRequest extends FormRequest
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
            'min_order_amount' => 'nullable|numeric|min:0|max_digits:5',
            'estimated_delivery_time' => 'required|integer|min:1|max:120',
            'prefix' => 'nullable|string|min:2|max:2',
            'opening_time' => 'required',
            'closing_time' => 'required|after:opening_time',
            'off_day' => 'nullable|array',
        ];
    }

    public function messages()
    {
        $request = request();
        if ($request->is('api/*')) {
            $lan = $request->header('accept-language') ?? 'en';
            app()->setLocale($lan);
        }

        return [
            'min_order_amount.min' => __('The min order amount must be at least 0.'),
            'min_order_amount.max_digits' => __('The min order amount must not be greater than 99999.'),
            'min_order_amount.numeric' => __('The min order amount must be a number.'),
            'min_order_amount.required' => __('The min order amount field is required.'),
            'estimated_delivery_time.min' => __('The estimated delivery time must be at least 1.'),
            'estimated_delivery_time.max' => __('The estimated delivery time must not be greater than 120.'),
            'estimated_delivery_time.required' => __('The estimated delivery time field is required.'),
            'prefix.min' => __('The prefix must be at least 2 characters.'),
            'prefix.max' => __('The prefix must not be greater than 2 characters.'),
            'opening_time.required' => __('The opening time field is required.'),
            'closing_time.required' => __('The closing time field is required.'),
            'closing_time.after' => __('The closing time must be after the opening time.'),
            'off_day.array' => __('The off day field must be an array.'),
        ];
    }
}
