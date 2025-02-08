<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|numeric|min:1|max:5',
            'description' => 'required|string',
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
            'product_id.required' => __('The product field is required.'),
            'product_id.exists' => __('The selected product is invalid.'),
            'order_id.required' => __('The order field is required.'),
            'order_id.exists' => __('The selected order is invalid.'),
            'rating.required' => __('The rating field is required.'),
            'rating.numeric' => __('The rating must be a number.'),
            'rating.min' => __('The rating must be at least 1.'),
            'rating.max' => __('The rating must be less than 5.'),
            'description.required' => __('The description field is required.'),
        ];
    }
}
