<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailSendOTPRequest extends FormRequest
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
        $forgotPassword = $this->forgot_password;

        $validate = $forgotPassword ? 'required|email|exists:users,email' : ['required', 'unique:users,email', new EmailRule()];

        return [
            'email' => $validate,
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
            'email.required' => __('The email field is required.'),
            'email.unique' => __('The email has already been taken.'),
            'email.exists' => __('The email is not registered.'),
        ];
    }
}
