<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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
            'id' => ['required', 'exists:banners,id'],
            'banner' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
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
            'id.required' => __('The banner id is required.'),
            'id.exists' => __('The selected banner id is invalid.'),
            'banner.required' => __('The banner field is required.'),
            'banner.image' => __('The banner must be an image.'),
            'banner.mimes' => __('The banner must be a file of type: jpeg, jpg, png, gif, webp.'),
        ];
    }
}
