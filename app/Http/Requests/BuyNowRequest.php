<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyNowRequest extends FormRequest
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
            'quantity' => 'nullable|integer',
            'coupon_code' => 'nullable|string|max:50',
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
            'quantity.integer' => __('The quantity must be an integer.'),
            'quantity.max' => __('The quantity may not be greater than 50 characters.'),
        ];
    }
}
