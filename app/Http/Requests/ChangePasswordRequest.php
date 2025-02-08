<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
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
            'current_password.required' => __('The current password field is required.'),
            'current_password.min' => __('The current password must be at least 6 characters.'),
            'password.min' => __('The new password must be at least 6 characters.'),
            'password.required' => __('The new password field is required.'),
            'password_confirmation.required' => __('The confirm password field is required.'),
            'password_confirmation.min' => __('The confirm password must be at least 6 characters.'),
        ];
    }
}
