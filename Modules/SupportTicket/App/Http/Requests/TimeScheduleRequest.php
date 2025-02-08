<?php

namespace Modules\SupportTicket\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeScheduleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'highlight' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
