<?php

namespace App\Http\Requests;

use App\Models\GeneraleSetting;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
        $required = 'required';
        if (request()->is('api/*')) {
            $required = 'nullable';
        }

        $generaleSetting = GeneraleSetting::first();

        $minAmount = $generaleSetting->min_withdraw ?? 0;
        $maxAmount = $generaleSetting->max_withdraw > 0 ? 'max:'.$generaleSetting->max_withdraw : null;

        return [
            'amount' => "required|numeric|min:$minAmount|$maxAmount",
            'name' => "$required|string|max:255",
            'contact_number' => "$required|string|max:255",
            'message' => 'nullable|string|max:255',
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
            'amount.required' => __('The amount field is required.'),
            'amount.numeric' => __('The amount must be a number.'),
            'name.required' => __('The name field is required.'),
            'contact_number.required' => __('The contact number field is required.'),
            'message.max' => __('The message must not be greater than 255 characters.'),
        ];
    }
}
