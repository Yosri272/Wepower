<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'name' => 'required|string|max:200',
            'phone' => 'required|unique:users,phone',
            'email' => ['nullable', 'email', new EmailRule()],
            'password' => 'required|string|min:6',
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
            'name.required' => __('The name field is required.'),
            'phone.required' => __('The phone field is required.'),
            'phone.unique' => __('Phone already exists.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 6 characters.'),
        ];
    }
}
