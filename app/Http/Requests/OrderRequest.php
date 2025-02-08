<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.shop_id' => 'required|exists:shops,id',
            'products.*.size' => 'nullable|string',
            'products.*.color' => 'nullable|string',
            'products.*.unit' => 'nullable|string',
            'address_id' => 'required|exists:addresses,id',
            'note' => 'nullable|string',
            'payment_method' => 'required|string',
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
            'products.required' => __('Please select a products.'),
            'paroducts.array' => __('The products must be an array.'),
            'products.*.id.required' => __('The product id field is required.'),
            'products.*.id.exists' => __('The product id is invalid.'),
            'products.*.quantity.required' => __('The quantity field is required.'),
            'products.*.quantity.min' => __('The quantity must be at least 1.'),
            'products.*.shop_id.required' => __('The shop id field is required.'),
            'products.*.shop_id.exists' => __('The shop id is invalid.'),
            'address_id.required' => __('The address id field is required.'),
            'address_id.exists' => __('The address id is invalid.'),
            'payment_method.required' => __('The payment method field is required.'),
        ];
    }
}
