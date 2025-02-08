<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderRiderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->driver->id,
            'name' => $this->driver->user->fullName,
            'phone' => $this->driver->user->phone,
            'profile_photo' => $this->driver->user->thumbnail,
            'assigned_at' => $this->created_at->format('d F, Y'),
        ];
    }
}
