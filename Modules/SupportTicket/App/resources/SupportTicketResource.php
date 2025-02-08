<?php

namespace Modules\SupportTicket\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
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
            'subject' => $this->subject,
            'issue_type' => $this->issue_type,
            'created_at' => $this->created_at->format('d F, Y'),
        ];
    }
}
