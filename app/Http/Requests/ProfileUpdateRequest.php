<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', new EmailRule()],
            'profile_photo' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,gif,svg',
            'phone' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
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
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.max' => __('The profile photo must not be greater than 2 MB.'),
            'phone.required' => __('The phone field is required.'),
            'gender.required' => __('The gender field is required.'),
            'date_of_birth.date' => __('The date of birth must be a valid date.'),
        ];
    }
}
