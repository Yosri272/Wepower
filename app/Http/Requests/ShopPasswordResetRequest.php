<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopPasswordResetRequest extends FormRequest
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
            'password' => 'required|string|min:6|confirmed',
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
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 6 characters.'),
            'password.confirmed' => __('The password and confirmation password do not match.'),
        ];
    }
}
