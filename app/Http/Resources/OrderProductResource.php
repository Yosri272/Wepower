<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $review = $this->reviews()->where('customer_id', auth()->user()->customer?->id)->where('product_id', $this->id)->where('order_id', $request->order_id)->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand?->name ?? null,
            'thumbnail' => $this->thumbnail,
            'price' => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'order_qty' => (int) $this->pivot->quantity,
            'color' => $this->pivot->color ?? null,
            'size' => $this->pivot->size ?? null,
            'rating' => $review ? (float) $review->rating : null,
            'pos_cart_id' => $this->pivot?->id ?? null
        ];
    }
}
