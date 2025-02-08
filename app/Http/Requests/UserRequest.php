<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'phone' => 'required|string|unique:users,phone,'.auth()->id(),
            'email' => ['nullable', 'email', 'max:255', new EmailRule()],
            'profile_photo' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,gif',
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
            'email.email' => __('The email must be a valid email address.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.max' => __('The profile photo must not be greater than 2 MB.'),
            'phone.required' => __('The phone field is required.'),
            'phone.unique' => __('The phone has already been taken.'),
            'email.unique' => __('The email has already been taken.'),
            'profile_photo.mimes' => __('The profile photo must be a file of type: jpg, jpeg, png, svg, webp, gif.'),
        ];
    }
}
