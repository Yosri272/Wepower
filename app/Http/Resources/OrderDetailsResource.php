<?php

namespace App\Http\Resources;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'order_status' => $this->order_status->value,
            'created_at' => $this->created_at,
            'placed_at' => $this->created_at->format('d M, Y h:i A'),
            'payment_method' => $paymentMethod,
            'payment_status' => $this->payment_status->value,
            'total_amount' => (float) number_format($this->total_amount, 2, '.', ''),
            'discount' => (float) number_format($this->discount, 2, '.', ''),
            'coupon_discount' => (float) number_format($this->coupon_discount, 2, '.', ''),
            'payable_amount' => (float) number_format($this->payable_amount, 2, '.', ''),
            'quantity' => (int) $this->products->sum('pivot.quantity'),
            'delivery_charge' => (float) number_format(($this->delivery_charge ?? 0), 2, '.', ''),
            'gift_charge' => (float) number_format($this->orderGift ? $this->orderGift->price : 0, 2, '.', ''),
            'shop' => ShopResource::make($this->shop),
            'products' => OrderProductResource::collection($this->products),
            'invoice_url' => route('shop.download-invoice', $this->id),
            'address' => AddressResource::make($this->address),
            'gift' => GiftOrderResource::make($this->orderGift),
        ];
    }
}
