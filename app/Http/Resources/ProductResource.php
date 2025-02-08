<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load(['reviews', 'orders']);

        $favorite = false;
        $user = Auth::guard('api')->user();

        if ($user && $user->customer) {
            $favoriteIDs = $user->customer?->favorites()?->pluck('product_id')->toArray() ?: [];
            $favorite = in_array($this->id, $favoriteIDs);
        }

        $discountPercentage = $this->getDiscountPercentage($this->price, $this->discount_price);

        $totalSold = $this->orders->sum('pivot.quantity');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'price' => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'discount_percentage' => (float) number_format($discountPercentage, 2, '.', ''),
            'rating' => (float) $this->averageRating > 0 ? $this->averageRating : 5.0,
            'total_reviews' => (string) Number::abbreviate($this->reviews->count(), maxPrecision: 2),
            'total_sold' => (string) number_format($totalSold, 0, '.', ','),
            'quantity' => (int) $this->quantity,
            'is_favorite' => (bool) $favorite,
            'sizes' => SizeResource::collection($this->sizes),
            'colors' => ColorResource::collection($this->colors),
            'units' => $this->units,
            'brand' => $this->brand?->name ?? null,
            'shop' => ProductShopResource::make($this->shop),
        ];
    }
}
