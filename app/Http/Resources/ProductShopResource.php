<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShopResource extends JsonResource
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
            'logo' => $this->logo,
            'rating' => (float) ($this->averageRating > 0) ? $this->averageRating : 5.0,
            'estimated_delivery_time' => (string) ($this->estimated_delivery_time ?? 4).' days',
            'delivery_charge' => (float) getDeliveryCharge(1),
        ];
    }
}
