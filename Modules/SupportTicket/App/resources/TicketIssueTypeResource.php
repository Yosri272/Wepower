<?php

namespace Modules\SupportTicket\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketIssueTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
