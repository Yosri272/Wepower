<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'quantity' => (int) $this->quantity,
            'product' => ProductResource::make($this->product),
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit,
        ];
    }
}
