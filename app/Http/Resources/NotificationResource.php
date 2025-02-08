<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->content,
            'type' => $this->type,
            'url' => $this->url,
            'created_at' => $this->created_at->diffForHumans(),
            'is_read' => (bool) $this->is_read,

        ];
    }
}
