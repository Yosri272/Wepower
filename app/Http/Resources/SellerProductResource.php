<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerProductResource extends JsonResource
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
            'name' => $this->name,
            'brand' => $this->brand?->name ?? null,
            'thumbnail' => $this->thumbnail,
            'price' => (float) $this->discount_price > 0 ? $this->discount_price : $this->price,
            'quantity' => (int) $this->pivot->quantity,
            'color' => $this->pivot->color ?? null,
            'size' => $this->pivot->size ?? null,
            'unit' => $this->pivot->unit ?? null,
        ];
    }
}
