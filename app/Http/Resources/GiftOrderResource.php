<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->gift->name,
            'price' => $this->price,
            'thumbnail' => $this->gift->thumbnail,
            'sender_name' => $this->sender_name,
            'receiver_name' => $this->receiver_name,
            'note' => $this->note,
            'address' => AddressResource::make($this->address),
            'created_at' => $this->created_at->format('d F, Y'),
        ];
    }
}
