<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopGiftRequest extends FormRequest
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
        $required = $this->isMethod('put') ? 'nullable' : 'required';

        return [
            'name' => 'required|string|max:80',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => "$required|image|mimes:png,jpg,jpeg,gif,svg|max:2048",
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
            'name.max' => __('The name may not be greater than 80 characters.'),
            'price.numeric' => __('The price must be a number.'),
            'price.min' => __('The price must be at least 0.'),
            'thumbnail.image' => __('The thumbnail must be an image.'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: png, jpg, jpeg, gif, svg.'),
        ];
    }
}
