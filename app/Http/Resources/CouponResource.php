<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $collectedCoupons = auth()->user()->coupons->pluck('coupon_id')->toArray();

        return [
            'id' => $this->id,
            'voucher_type' => (string) $this->shop_id ? 'Shop Voucher' : 'Amin Voucher',
            'code' => (string) $this->code,
            'discount_type' => $this->type->value,
            'discount' => (float) $this->discount,
            'min_order_amount' => (float) $this->min_amount,
            'max_discount_amount' => (float) $this->max_discount_amount,
            'limit_for_user' => (int) $this->limit_for_user,
            'shop_id' => $this->shop_id ?? null,
            'started' => Carbon::parse($this->started_at)->format('d F Y H:i'),
            'validity' => Carbon::parse($this->expired_at)->format('d F Y H:i'),
            'is_collected' => (bool) in_array($this->id, $collectedCoupons) ? true : false,
        ];
    }
}
