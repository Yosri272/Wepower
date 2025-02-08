<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosCartRequest extends FormRequest
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
            'quantity' => 'required|min:1',
            'color' => 'nullable',
            'size' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => __('The product field is required.'),
            'quantity.required' => __('The quantity field is required.'),
            'quantity.min' => __('The quantity must be at least 1.'),
        ];
    }
}
