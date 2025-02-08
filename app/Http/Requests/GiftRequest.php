<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftRequest extends FormRequest
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
            'gift_id' => 'required|exists:gifts,id',
            'product_id' => 'required|exists:products,id',
            'receiver_name' => 'nullable|string|max:255',
            'sender_name' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'address_id' => 'nullable|exists:addresses,id',
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
            'gift_id.required' => __('The gift id field is required.'),
            'gift_id.exists' => __('The gift id is invalid.'),
            'product_id.required' => __('The product id field is required.'),
            'product_id.exists' => __('The product id is invalid.'),
        ];
    }
}
