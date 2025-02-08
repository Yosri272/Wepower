<?php

namespace App\Http\Resources;

use App\Enums\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderOrderDetailsResource extends JsonResource
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
            'order_code' => (string) '#'.$this->prefix.''.$this->order_code,
            'amount' => (float) number_format($this->payable_amount, 2, '.', ''),
            'order_status' => $this->order_status->value,
            'payment_status' => $this->payment_status->value,
            'payment_method' => $this->payment_method->value == PaymentMethod::CASH->value ? 'Cash' : 'Online',
            'estimated_delivery_date' => Carbon::parse($this->created_at)->addDays(5)->format('d F, Y'),
            'pickup_date' => $this->pickup_date ? Carbon::parse($this->pickup_date)->format('d F, Y') : null,
            'user' => [
                'name' => $this->customer->user->name,
                'phone' => $this->customer->user->phone,
                'profile_photo' => $this->customer->user->thumbnail,
                'address' => AddressResource::make($this->address),
            ],
            'shop' => [
                'name' => $this->shop->name,
                'logo' => $this->shop->logo,
                'phone' => $this->shop->user->phone,
                'address' => $this->shop->address,
            ],
        ];
    }
}
