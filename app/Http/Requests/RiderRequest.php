<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class RiderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = 'required';
        $userId = null;
        if ($this->routeIs('rider.profile.update', 'admin.rider.update')) {
            $required = 'nullable';
            $userId = $this->routeIs('admin.rider.update') ? $this->user->id : auth()->user()->id;
        }

        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'phone' => 'required|unique:users,phone,'.$userId,
            'email' => ['nullable', new EmailRule(), 'unique:users,email,'.$userId],
            'password' => "$required|min:6|confirmed",
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'driving_lience' => 'nullable|string',
            'vehicle_type' => 'required|string',
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
            'first_name.required' => __('The first name field is required.'),
            'phone.required' => __('The phone field is required.'),
            'phone.unique' => __('The phone has already been taken.'),
            'email.unique' => __('The email has already been taken.'),
            'password.min' => __('The password must be at least 6 characters.'),
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password and confirmation password do not match.'),
            'vehicle_type.required' => __('The vehicle type field is required.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.mimes' => __('The profile photo must be a file of type: jpg, jpeg, png, svg.'),
            'profile_photo.max' => __('The profile photo may not be greater than 2MB.'),
        ];
    }
}
