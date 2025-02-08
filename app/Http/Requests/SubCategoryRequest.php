<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
            'category' => ['required', 'array', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
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
            'category.required' => __('The category field is required.'),
            'category.exists' => __('The selected category is invalid.'),
            'name.required' => __('The name field is required.'),
            'thumbnail.image' => __('The thumbnail must be an image.'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: jpg, jpeg, png, gif.'),
            'thumbnail.max' => __('The thumbnail must not be greater than 2 kilobytes.'),
            'thumbnail.required' => __('The thumbnail field is required.'),
        ];
    }
}
