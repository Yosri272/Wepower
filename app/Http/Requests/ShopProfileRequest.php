<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class ShopProfileRequest extends FormRequest
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
        $user = auth()->user();

        // validation rules
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,'.$user?->id, new EmailRule()],
            'gender' => ['nullable', 'string'],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'address' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'shop_banner' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'description' => ['nullable', 'string', 'max:255'],
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
            'phone.required' => __('The phone field is required.'),
            'email.unique' => __('The email has already been taken.'),
            'email.required' => __('The email field is required.'),
            'profile_photo.max' => __('The profile photo may not be greater than 2048 kilobytes.'),
            'shop_logo.max' => __('The shop logo may not be greater than 2048 kilobytes.'),
            'shop_banner.max' => __('The shop banner may not be greater than 2048 kilobytes.'),
            'opening_time.required' => __('The opening time field is required.'),
            'closing_time.required' => __('The closing time field is required.'),
            'closing_time.after' => __('The closing time must be after the opening time.'),
            'off_day.array' => __('The off day field must be an array.'),
            'min_order_amount.min' => __('The min order amount must be at least 0.'),
            'min_order_amount.max_digits' => __('The min order amount must not be greater than 99999.'),
            'min_order_amount.numeric' => __('The min order amount must be a number.'),
            'min_order_amount.required' => __('The min order amount field is required.'),
            'estimated_delivery_time.min' => __('The estimated delivery time must be at least 1.'),
            'estimated_delivery_time.max' => __('The estimated delivery time must not be greater than 120.'),
            'estimated_delivery_time.required' => __('The estimated delivery time field is required.'),
            'prefix.min' => __('The prefix must be at least 2 characters.'),
            'prefix.max' => __('The prefix must not be greater than 2 characters.'),
            'password.min' => __('The password must be at least 6 characters.'),
            'password.confirmed' => __('The password and confirmation password do not match.'),
            'password.required' => __('The password field is required.'),
        ];
    }
}
