<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class GeneraleSettingRequest extends FormRequest
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
            'name' => 'nullable|string',
            'title' => 'nullable|string',
            'email' => ['nullable', 'email', 'max:255', new EmailRule()],
            'mobile' => 'nullable|string',
            'address' => 'nullable|string',
            'google_playstore_url' => 'nullable|string',
            'app_store_url' => 'nullable|string',
            'currency' => 'nullable|string|max:4',
            'currency_position' => 'nullable|string',
            'direction' => 'nullable|string',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,png,svg,webp|max:2048',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,png,svg|max:2048',
            'footer_phone' => 'nullable|string',
            'footer_email' => ['nullable', 'email', new EmailRule()],
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
            'currency.max' => __('The currency code must be a maximum of 4 characters.'),
            'favicon.image' => __('The favicon must be an image.'),
            'logo.image' => __('The logo must be an image.'),
            'favicon.mimes' => __('The favicon must be a file of type: png, jpg, jpeg, png, svg, webp.'),
            'logo.mimes' => __('The logo must be a file of type: png, jpg, jpeg, png, svg, webp.'),
        ];
    }
}
