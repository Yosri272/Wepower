<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $estimatedDelivery = $this->delivered_at ? Carbon::parse($this->delivered_at)->format('d M, Y h:i A') : null;
        if (! $this->delivered_at) {
            $shopEstimate = $this->shop->estimated_delivery_time ?? 3;
            $estimatedDelivery = Carbon::parse($this->created_at)->addDays($shopEstimate)->format('d M, Y');
        }

        return [
            'order_status' => $this->order_status,
            'total_amount' => $this->total_amount,
            'discount' => $this->discount,
            'delivery_charge' => $this->delivery_charge,
            'payable_amount' => $this->payable_amount,
            'estimated_delivery_time' => $estimatedDelivery,
            'address' => AddressResource::make($this->order?->address),
        ];
    }
}
