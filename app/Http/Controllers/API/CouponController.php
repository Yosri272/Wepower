<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\VoucherRequest;
use App\Http\Resources\CouponResource;
use App\Repositories\CouponCollectRepository;
use App\Repositories\CouponRepository;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * get shop voucher from shop
     */
    public function index(VoucherRequest $request)
    {
        $shopId = $request->shop_id;

        $coupons = CouponRepository::query()->whereShopId($shopId)->Active()->isValid()->get();

        // $coupons = CouponRepository::query()->whereShopId($shopId)
        //     ->orWhereHas('shops', function ($query) use ($shopId) {
        //         $query->where('id', $shopId);
        //     })->Active()->isValid()->get();

        return $this->json('Shop vouchers', [
            'coupons' => CouponResource::collection($coupons),
        ]);
    }

    /**
     * collect voucher
     * */
    public function store(VoucherRequest $request)
    {
        $hasExistCoupon = CouponCollectRepository::hasExistCoupon($request);

        if ($hasExistCoupon) {
            return $this->json('Voucher already collected');
        }

        $coupon = CouponCollectRepository::storeByRequest($request);

        return $this->json('Voucher collected successfully', [
            'coupon' => CouponResource::make($coupon->coupon),
        ]);
    }

    /**
     * get collected vouchers
     *
     * @param  VoucherRequest  $request
     * */
    public function collectedVouchers(Request $request)
    {
        // get shop id
        $shopId = $request->shop_id;

        // get collected vouchers from repository
        $coupons = CouponRepository::getCollectedCoupons($shopId);

        return $this->json('available collected vouchers', [
            'coupons' => CouponResource::collection($coupons),
        ]);
    }

    /**
     * Apply voucher from user collected vouchers
     */
    public function applyVoucher(ApplyCouponRequest $request)
    {
        $couponDiscount = CouponRepository::getCouponDiscount($request);

        $message = $couponDiscount['discount_amount'] > 0 ? 'Voucher applied successfully' : 'Voucher not applied';

        $status = $couponDiscount['discount_amount'] > 0 ? 200 : 201;

        return $this->json($message, [
            'total_order_amount' => (float) number_format($couponDiscount['total_amount'], 2, '.', ''),
            'total_discount_amount' => (float) number_format($couponDiscount['discount_amount'], 2, '.', ''),
        ], $status);
    }

    /**
     * Apply coupon from coupon code
     * */
    public function getDiscount(ApplyCouponRequest $request)
    {
        $couponDiscount = CouponRepository::getCouponDiscount($request);

        $message = $couponDiscount['discount_amount'] > 0 ? 'Voucher applied successfully' : 'Voucher not applied';

        $status = $couponDiscount['discount_amount'] > 0 ? 200 : 201;

        return $this->json($message, [
            'total_order_amount' => (float) round($couponDiscount['total_amount'], 2),
            'total_discount_amount' => (float) round($couponDiscount['discount_amount'], 2),
        ], $status);
    }
}
