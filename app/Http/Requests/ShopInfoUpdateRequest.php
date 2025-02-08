<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopInfoUpdateRequest extends FormRequest
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
        // validation rules
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif', 'max:2048'],
            'address' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
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
            'logo.image' => __('The logo must be an image.'),
            'logo.max' => __('The logo must not be greater than 2 MB.'),
            'banner.image' => __('The banner must be an image.'),
            'banner.max' => __('The banner must not be greater than 2 MB.'),
            'logo.mimes' => __('The logo must be a file of type: jpg, png, jpeg, gif, svg.'),
            'banner.mimes' => __('The banner must be a file of type: jpg, png, jpeg, gif, svg.'),
        ];
    }
}
