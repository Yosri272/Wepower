<?php

namespace Modules\SupportTicket\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'created_at' => $this->created_at->format('d F, Y'),
            'sender_id' => $this->sender_id,
            'sender_name' => $this->sender->name,
            'profile_photo' => $this->sender->thumbnail,
            'is_highlighted' => (bool) $this->is_highlighted,
        ];
    }
}
