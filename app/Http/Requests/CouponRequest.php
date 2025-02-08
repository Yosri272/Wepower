<?php

namespace App\Http\Requests;

use App\Enums\DiscountType;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $discountType = array_column(DiscountType::cases(), 'value');
        $coupon = $this->coupon ?? null;

        return [
            'code' => ['required', 'string', 'max:255', 'unique:coupons,code,'.$coupon?->id.',id'],
            'discount' => ['required', 'numeric', 'min:0'],
            'discount_type' => ['required', 'in:'.implode(',', $discountType)],
            'start_date' => ['required', 'date'],
            'start_time' => ['required'],
            'expired_date' => ['required', 'date'],
            'expired_time' => ['required'],
            'min_order_amount' => ['required', 'numeric', 'min:0'],
            'limit_for_user' => ['nullable', 'integer', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'shops' => ['nullable', 'array'],
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
            'code.required' => __('The code field is required.'),
            'code.unique' => __('The code has already been taken.'),
            'discount.required' => __('The discount field is required.'),
            'discount.numeric' => __('The discount must be a number.'),
            'discount_type.required' => __('The discount type field is required.'),
            'start_date.required' => __('The start date field is required.'),
            'start_time.required' => __('The start time field is required.'),
            'expired_date.required' => __('The expired date field is required.'),
            'expired_time.required' => __('The expired time field is required.'),
            'min_order_amount.required' => __('The min order amount field is required.'),
        ];
    }
}
