<?php

namespace App\Http\Resources;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paymentMethod = $this->payment_method->value;
        if ($this->payment_status->value == PaymentStatus::PENDING->value && $paymentMethod != PaymentMethod::CASH->value) {
            $paymentMethod = PaymentMethod::ONLINE->value;
        }

        return [
            'id' => $this->id,
            'order_code' => (string) '#'.$this->prefix.''.$this->order_code,
            'quantity' => (int) $this->products->sum('pivot.quantity'),
            'amount' => (float) number_format($this->payable_amount, 2, '.', ''),
            'payment_method' => $paymentMethod,
            'payment_status' => $this->payment_status->value,
            'order_status' => $this->order_status->value,
            'created_at' => $this->created_at,
            'placed_at' => $this->created_at->format('d M, Y h:i A'),
            'address' => AddressResource::make($this->address),
            'gift' => GiftOrderResource::make($this->orderGift),
        ];
    }
}
