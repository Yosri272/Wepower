<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $addtionThumbnail = $this->product?->medias?->isNotEmpty() ? 'nullable' : 'required';
        $thumbnail = $this->product?->media ? 'nullable' : 'required';

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:200',
            'category' => 'required|exists:categories,id',
            'sub_category' => 'nullable|array|exists:sub_categories,id',
            'brand' => 'nullable|exists:brands,id',
            'code' => 'required|numeric|digits_between:5,7|unique:products,code,'.$this->product?->id,
            'color' => 'nullable|array',
            'size' => 'nullable|array',
            'size.*.id' => 'nullable|exists:sizes,id',
            'size.*.price' => 'nullable|numeric|min:0',
            'unit' => 'nullable|exists:units,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|max:'.$this->price,
            'quantity' => 'required|integer|min:0',
            'min_order_quantity' => 'required|integer|min:0',
            'thumbnail' => "$thumbnail|image|mimes:png,jpg,jpeg,webp|max:2048",
            'additionThumbnail' => "$addtionThumbnail|array",
            'additionThumbnail.*' => 'image|mimes:png,jpg,jpeg,webp|max:2048',

            'previousThumbnail' => 'nullable|array',
            'previousThumbnail.*.id' => 'nullable|exists:media,id',
            'previousThumbnail.*.file' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:2048',
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
            'name.max' => __('The name may not be greater than 255 characters.'),
            'description.required' => __('The description field is required.'),
            'short_description.required' => __('The short description field is required.'),
            'short_description.max' => __('The short description may not be greater than 200 characters.'),
            'category.required' => __('The category field is required.'),
            'category.exists' => __('The selected category is invalid.'),
            'code.required' => __('The code field is required.'),
            'code.unique' => __('The code has already been taken.'),
            'code.numeric' => __('The code must be a number.'),
            'code.digits_between' => __('The code must be 5-7 digits.'),
            'price.required' => __('The price field is required.'),
            'price.numeric' => __('The price must be a number.'),
            'price.min' => __('The price must be at least 0.'),
            'discount_price.numeric' => __('The discount price must be a number.'),
            'discount_price.min' => __('The discount price must be at least 0.'),
            'discount_price.max' => __('The discount price must be less than price.'),
            'quantity.required' => __('The quantity field is required.'),
            'quantity.integer' => __('The quantity must be an integer.'),
            'quantity.min' => __('The quantity must be at least 0.'),
            'min_order_quantity.required' => __('The min order quantity field is required.'),
            'min_order_quantity.integer' => __('The min order quantity must be an integer.'),
            'min_order_quantity.min' => __('The min order quantity must be at least 0.'),
            'thumbnail.required' => __('The thumbnail field is required.'),
            'thumbnail.image' => __('The thumbnail must be an image.'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: png, jpg, jpeg, webp.'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes.'),
            'additionThumbnail.required' => __('The addition thumbnail field is required.'),
            'additionThumbnail.image' => __('The addition thumbnail must be an image.'),
            'additionThumbnail.mimes' => __('The addition thumbnail must be a file of type: png, jpg, jpeg, webp.'),
            'additionThumbnail.max' => __('The addition thumbnail may not be greater than 2048 kilobytes.'),
        ];
    }
}
