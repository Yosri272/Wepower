<?php

namespace Modules\SupportTicket\App\resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_no' => $this->ticket_number,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'user_chat' => (bool) $this->user_chat,
            'subject' => $this->subject,
            'issue_type' => $this->issue_type,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at->format('d F, Y'),
            'ticket_schedule_start' => $this->ticket_start ? Carbon::parse($this->ticket_start)->format('d F, Y h:i A') : null,
            'ticket_schedule_end' => $this->ticket_end ? Carbon::parse($this->ticket_end)->format('d F, Y h:i A') : null,
            'attachments' => $this->attachments,
            'messages' => SupportTicketMessageResource::collection($this->messages),
        ];
    }
}
