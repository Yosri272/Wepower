<?php

namespace App\Repositories;

use App\Enums\DiscountType;
use App\Http\Requests\CouponRequest;
use App\Models\AdminCoupon;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Shop;

class CouponRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Coupon::class;
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function storeByRequest(CouponRequest $request, $shopId): Coupon
    {
        return self::create([
            'code' => $request->code,
            'discount' => $request->discount,
            'type' => $request->discount_type,
            'started_at' => $request->start_date.' '.$request->start_time,
            'expired_at' => $request->expired_date.' '.$request->expired_time,
            'min_amount' => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'limit_for_user' => $request->limit_for_user,
            'shop_id' => $shopId,
            'is_active' => true,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public static function updateByRequest(CouponRequest $request, Coupon $coupon): Coupon
    {
        $coupon->update([
            'code' => $request->code,
            'discount' => $request->discount,
            'type' => $request->discount_type,
            'started_at' => $request->start_date.' '.$request->start_time,
            'expired_at' => $request->expired_date.' '.$request->expired_time,
            'min_amount' => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'limit_for_user' => $request->limit_for_user,
        ]);

        return $coupon;
    }

    /**
     * Check the validity of a coupon.
     *
     * @param  mixed  $coupon  The coupon object to be checked
     * @return mixed The valid coupon object or false
     */
    public static function checkValidity($coupon = null)
    {
        if ($coupon && ($coupon->started_at >= now()) && ($coupon->expired_at <= now())) {
            return $coupon;
        }

        return false;
    }

    /**
     * Get the collected coupons.
     *
     * @param  mixed  $shopId
     * @return mixed
     */
    public static function getCollectedCoupons($shopId = null)
    {
        $coupons = self::query()->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->when($shopId, function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        })->Active()->isValid()->get();

        if ($shopId) {
            $adminCoupons = AdminCoupon::where('shop_id', $shopId)->get();
            $userCollectedCoupons = CouponCollectRepository::query()->where('user_id', auth()->id())->pluck('coupon_id')->toArray();

            foreach ($adminCoupons as $adminCoupon) {
                if (in_array($adminCoupon->coupon_id, $userCollectedCoupons)) {
                    $coupons->push($adminCoupon->coupon);
                }
            }
        }

        return $coupons;
    }

    /**
     * Get coupon discount amount and total amount
     *
     * @param  mixed  $request
     * @return array
     */
    public static function getCouponDiscount($request)
    {
        $productsCollection = collect($request->products);
        $shopProducts = $productsCollection->groupBy('shop_id');

        $totalOrderAmount = 0; // total amount
        $totalDiscountAmount = 0;

        $coupon = null;

        $couponCode = $request->coupon_code ?? null;

        foreach ($shopProducts as $shopId => $products) {

            $totalAmount = 0; // total amount
            foreach ($products as $productArray) {
                $product = Product::find($productArray['id']);
                $totalAmount += (float) ($product->discount_price > 0 ? $product->discount_price : $product->price) * $productArray['quantity'];
            }

            if ($couponCode) {
                $shop = Shop::find($shopId);
                $coupon = $shop->coupons()->where('code', $couponCode)->Active()->isValid()->first();

                if (! $coupon) {
                    $coupon = AdminCoupon::where('shop_id', $shopId)->whereHas('coupon', function ($query) use ($couponCode) {
                        $query->where('code', $couponCode)->Active()->isValid();
                    })->first()?->coupon;
                }

                if ($coupon) {
                    $discount = self::getCouponDiscountAmount($coupon, $totalAmount);

                    $totalOrderAmount += (float) $discount['total_amount'];
                    $totalDiscountAmount += (float) $discount['discount_amount'];
                }
            } else {

                $collectedCoupons = CouponRepository::getCollectedCoupons($shopId);

                foreach ($collectedCoupons as $collectedCoupon) {

                    $discount = self::getCouponDiscountAmount($collectedCoupon, $totalAmount);

                    $totalOrderAmount += (float) $discount['total_amount'];

                    if ($discount['discount_amount'] > 0) {
                        $coupon = $collectedCoupon;
                        $totalDiscountAmount += (float) $discount['discount_amount'];
                        break;
                    }
                }
            }
        }

        return [
            'total_amount' => $totalOrderAmount,
            'discount_amount' => $totalDiscountAmount,
            'coupon' => $coupon,
        ];
    }

    /**
     * Get coupon discount amount
     *
     * @param  mixed  $coupon
     * @param  mixed  $totalAmount
     * @return array
     */
    public static function getCouponDiscountAmount($coupon, $totalAmount)
    {
        $appliedOrders = OrderRepository::getAppliedCouponOrders($coupon);

        $amount = $coupon->type->value == DiscountType::PERCENTAGE->value ? ($totalAmount * $coupon->discount) / 100 : $coupon->discount;

        $couponDiscount = 0;
        if ($appliedOrders?->count() < ($coupon->limit_for_user ?? 500) && $coupon->min_amount <= $totalAmount) {
            $couponDiscount = $amount;
            if ($coupon->max_discount_amount && $coupon->max_discount_amount < $amount) {
                $couponDiscount = $coupon->max_discount_amount;
            }
        }

        return [
            'total_amount' => $totalAmount,
            'discount_amount' => (float) round($couponDiscount ?? 0, 2),
        ];
    }
}
