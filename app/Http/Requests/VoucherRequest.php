<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
        $shop = 'required';
        $coupon = 'nullable';

        if ($this->routeIs('voucher.collect')) {
            $shop = 'nullable';
            $coupon = 'required';
        }

        return [
            'shop_id' => "$shop|exists:shops,id",
            'coupon_id' => "$coupon|exists:coupons,id",
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
            'shop_id.required' => __('The shop id field is required.'),
            'coupon_id.required' => __('The coupon id field is required.'),
            'coupon_id.exists' => __('The coupon id is not exists.'),
            'shop_id.exists' => __('The shop id is not exists.'),
        ];
    }
}
