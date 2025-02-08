<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'thumbnail' => [$required, 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
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
            'name.string' => __('The name must be a string.'),
            'name.max' => __('The name may not be greater than 255 characters.'),
            'thumbnail.required' => __('The thumbnail field is required.'),
            'thumbnail.image' => __('The thumbnail must be an image.'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: jpg, jpeg, png, gif.'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes.'),
        ];
    }
}
