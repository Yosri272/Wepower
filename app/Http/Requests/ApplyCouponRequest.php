<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
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
        $required = $this->routeIs('voucher.apply') ? 'nullable' : 'required';

        return [
            'coupon_code' => "$required|string|max:50",
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.shop_id' => 'required|exists:shops,id',
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
            'coupon_code.required' => __('The coupon code field is required'),
            'coupon_code.max' => __('The coupon code may not be greater than 50 characters'),
            'products.required' => __('The products field is required'),
            'products.*.id.required' => __('The product id field is required'),
            'products.*.id.exists' => __('The selected product id is invalid'),
            'products.*.quantity.required' => __('The quantity field is required'),
            'products.*.quantity.integer' => __('The quantity must be an integer'),
            'products.*.quantity.min' => __('The quantity must be at least 1'),
            'products.*.shop_id.required' => __('The shop id field is required'),
            'products.*.shop_id.exists' => __('The selected shop id is invalid'),
        ];
    }
}
