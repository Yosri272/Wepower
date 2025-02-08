<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'customer_name' => $this->customer->user->name,
            'customer_profile' => $this->customer->user->thumbnail,
            'rating' => (float) $this->rating,
            'description' => $this->description,
            'created_at' => $this->created_at->format('F d, Y'),
        ];
    }
}
