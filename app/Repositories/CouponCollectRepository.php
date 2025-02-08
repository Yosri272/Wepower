<?php

namespace App\Repositories;

use App\Http\Requests\VoucherRequest;
use App\Models\CouponCollect;

class CouponCollectRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return CouponCollect::class;
    }

    /**
     * create new coupon collect
     */
    public static function storeByRequest(VoucherRequest $request): CouponCollect
    {
        return self::create([
            'user_id' => auth()->id(),
            'coupon_id' => $request->coupon_id,
        ]);
    }

    /**
     * check coupon collect
     * */
    public static function hasExistCoupon(VoucherRequest $request)
    {
        return self::query()->where('user_id', auth()->id())
            ->where('coupon_id', $request->coupon_id)
            ->exists();
    }
}
