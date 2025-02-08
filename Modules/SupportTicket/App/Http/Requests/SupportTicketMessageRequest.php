<?php

namespace Modules\SupportTicket\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ticket_number' => 'required|exists:support_tickets,ticket_number',
            'message' => 'required|string|max:5000',
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
