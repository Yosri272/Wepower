<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyNowStoreRequest extends FormRequest
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
            'address_id' => 'required|exists:addresses,id',
            'note' => 'nullable|string',
            'payment_method' => 'required|string',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'unit' => 'nullable|string',
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
            'address_id.required' => __('The address field is required.'),
            'address_id.exists' => __('The selected address is invalid.'),
            'payment_method.required' => __('The payment method field is required.'),
        ];
    }
}
