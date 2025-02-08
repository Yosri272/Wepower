<?php

namespace App\Http\Controllers\Shop;

use App\Enums\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Repositories\CouponRepository;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = auth()->user()->shop->coupons()->paginate(20)->withQueryString();

        return view('shop.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $discountTypes = DiscountType::cases();

        return view('shop.coupon.create', compact('discountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        CouponRepository::storeByRequest($request, auth()->user()->shop->id);

        return to_route('shop.voucher.index')->withSuccess(__('Voucher created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $discountTypes = DiscountType::cases();

        return view('shop.coupon.edit', compact('coupon', 'discountTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        CouponRepository::updateByRequest($request, $coupon);

        return to_route('shop.voucher.index')->withSuccess(__('Voucher updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->withSuccess(__('Voucher deleted successfully'));
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function statusToggle(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => ! $coupon->is_active,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }
}
