<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentTime = Carbon::now();

        $openingTime = $this->opening_time;
        $closingTime = $this->closing_time;

        // Parse opening and closing times using Carbon
        $openingTime = Carbon::parse($openingTime)->format('H:i');
        $closingTime = Carbon::parse($closingTime)->format('H:i');

        $shopStatus = 'Offline';
        // Check if the current time is between opening and closing times
        if ($currentTime->between($openingTime, $closingTime)) {
            $shopStatus = 'Online';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo,
            'banner' => $this->banner,
            'total_products' => (int) $this->products()->isActive()->count(),
            'total_categories' => (int) $this->categories()->active()->count(),
            'rating' => (float) number_format($this->averageRating, 1, '.', ''),
            'shop_status' => (string) $shopStatus,
            'total_reviews' => (string) Number::abbreviate($this->reviews->count(), maxPrecision: 2),
        ];
    }
}
