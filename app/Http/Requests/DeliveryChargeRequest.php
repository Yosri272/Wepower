<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryChargeRequest extends FormRequest
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
        $acceptId = $this->deliveryCharge?->id ?? null;

        return [
            'delivery_charge' => ['required', 'numeric'],
            'min_order_qty' => ['required', 'integer', 'min:0', 'unique:delivery_charges,min_qty,'.$acceptId],
            'max_order_qty' => ['required', 'integer', 'min:'.$this->min_order_qty, 'unique:delivery_charges,max_qty,'.$acceptId],
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
            'delivery_charge.required' => __('The delivery charge field is required.'),
            'delivery_charge.numeric' => __('The delivery charge must be a number.'),
            'min_order_qty.unique' => __('The min order qty has already been taken.'),
            'max_order_qty.unique' => __('The max order qty has already been taken.'),
            'max_order_qty.min' => __('The max order qty must be greater than min order qty.'),
        ];
    }
}
