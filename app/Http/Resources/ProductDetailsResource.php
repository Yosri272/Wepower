<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load(['reviews', 'orders', 'colors', 'shop']);

        $favorite = false;
        $user = Auth::guard('api')->user();
        if ($user) {
            $favoriteIDs = $user->customer->favorites()->pluck('product_id')->toArray();
            $favorite = in_array($this->id, $favoriteIDs);
        }

        $discountPercentage = $this->getDiscountPercentage($this->price, $this->discount_price);

        $shopDistance = null;

        if ($request->has('latitude') && $request->has('longitude')) {
            $shopDistance = getDistance([$request->latitude, $request->longitude], [$this->shop->latitude, $this->shop->longitude]);
        }

        $totalSold = $this->orders->sum('pivot.quantity');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'price' => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'discount_percentage' => (float) number_format($discountPercentage, 2, '.', ''),
            'rating' => (float) ($this->averageRating > 0) ? $this->averageRating : 5.0,
            'total_reviews' => (string) Number::abbreviate($this->reviews->count(), maxPrecision: 2),
            'total_sold' => (string) number_format($totalSold, 0, '.', ','),
            'quantity' => (int) $this->quantity,
            'is_favorite' => (bool) $favorite,
            'thumbnails' => $this->thumbnails(),
            'sizes' => SizeResource::collection($this->sizes),
            'colors' => ColorResource::collection($this->colors),
            'brand' => $this->brand?->name ?? null,
            'units' => $this->units,
            'shop' => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'logo' => $this->shop->logo,
                'rating' => (float) round($this->shop->averageRating, 1),
                'estimated_delivery_time' => (string) ($this->shop->estimated_delivery_time ?? 3).' days',
                'delivery_charge' => (float) getDeliveryCharge(1),
            ],
            'description' => $this->description,
        ];
    }
}
