<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentTime = Carbon::now();

        // Parse opening and closing times using Carbon
        $openingTime = Carbon::parse($this->shop->opening_time)->format('H:i');
        $closingTime = Carbon::parse($this->shop->closing_time)->format('H:i');

        $shopStatus = 'Offline';

        // Check if the current time is between opening and closing times
        if ($currentTime->between($openingTime, $closingTime)) {
            $shopStatus = 'Online';
        }

        $offDay = $this->shop->off_day ?? [];

        if (! empty($offDay)) {
            $offDay = is_array($offDay) ? $offDay : json_decode($offDay);
            $day = strtolower($currentTime->format('D'));
            in_array($day, $offDay) ? $shopStatus = 'Offline' : false;
        }

        return [
            'id' => $this->id,
            'first_name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'profile_photo' => $this->thumbnail,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'is_active' => (bool) $this->is_active,
            'shop_status' => (string) $shopStatus,
            'shop' => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'logo' => $this->shop->logo,
                'banner' => $this->shop->banner,
                'address' => $this->shop->address,
                'open_time' => $openingTime,
                'close_time' => $closingTime,
                'off_day' => $offDay,
                'prefix' => $this->shop->prefix ?? 'RC',
                'estimated_delivery_time' => (int) $this->shop->estimated_delivery_time,
                'min_order_amount' => (float) $this->shop->min_order_amount ?? 0,
                'shop_status' => $shopStatus,
                'total_products' => (int) $this->shop->products->count(),
                'total_categories' => (int) $this->shop->categories->count(),
                'rating' => (float) number_format($this->shop->averageRating, 1, '.', ''),
                'total_reviews' => (int) $this->shop->reviews->count(),
                'description' => $this->shop->description,
            ],
            'banners' => BannerResource::collection($this->shop->banners),
        ];
    }
}
