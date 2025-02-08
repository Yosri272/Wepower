<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class ShopCreateRequest extends FormRequest
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
        $user = null;
        $required = 'required';
        if ($this->routeIs('admin.shop.update')) {
            $user = $this->shop?->user;
            $required = 'nullable';
        }

        // validation rules
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'unique:users,phone,'.$user?->id, 'digits_between:9,16'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user?->id, new EmailRule()],
            'Gender' => ['nullable', 'string'],
            'password' => [$required, 'min:6', 'confirmed'],
            'password_confirmation' => [$required, 'min:6'],
            'address' => ['nullable', 'string'],
            'profile_photo' => [$required, 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_logo' => [$required, 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'shop_banner' => [$required, 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'description' => ['nullable', 'string', 'max:255'],
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
            'phone.unique' => __('The phone has already been taken.'),
            'email.required' => __('The email field is required.'),
            'email.unique' => __('The email has already been taken.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 6 characters.'),
            'password.confirmed' => __('The password and confirmation password do not match.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.max' => __('The profile photo must not be greater than 2 MB.'),
            'shop_name.required' => __('The shop name field is required.'),
            'shop_logo.image' => __('The shop logo must be an image.'),
            'shop_logo.max' => __('The shop logo must not be greater than 2 MB.'),
            'shop_banner.image' => __('The shop banner must be an image.'),
            'shop_banner.max' => __('The shop banner must not be greater than 2 MB.'),
            'description.max' => __('The description may not be greater than 255 characters.'),
            'password_confirmation.min' => __('The password confirmation must be at least 6 characters.'),
            'password_confirmation.required' => __('The password confirmation field is required.'),
            'address.max' => __('The address may not be greater than 255 characters.'),
        ];
    }
}
