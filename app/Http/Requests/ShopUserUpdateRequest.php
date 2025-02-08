<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class ShopUserUpdateRequest extends FormRequest
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

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone,'.$user?->id],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,'.$user?->id, new EmailRule()],
            'gender' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,svg,webp,gif'],
            'date_of_birth' => ['nullable', 'date'],
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
            'first_name.required' => __('The first name field is required.'),
            'phone.required' => __('The phone field is required.'),
            'phone.unique' => __('The selected phone is invalid.'),
            'email.unique' => __('The selected email is invalid.'),
            'profile_photo.max' => __('The profile photo may not be greater than 2MB.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.mimes' => __('The profile photo must be a file of type: jpg, jpeg, png, svg, webp, gif.'),
            'date_of_birth.date' => __('The date of birth must be a valid date.'),
        ];
    }
}
