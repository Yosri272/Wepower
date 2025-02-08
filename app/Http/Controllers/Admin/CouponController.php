<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountType;
use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Models\GeneraleSetting;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\CouponRepository;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $coupons = Coupon::whereNull('shop_id')->paginate(20)->withQueryString();

        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $discountTypes = DiscountType::cases();

        $generalSetting = GeneraleSetting::first();

        if ($generalSetting?->shop_type == 'single') {
            $rootUser = User::role(Roles::ROOT->value)->first();
            $shops = Shop::where('user_id', $rootUser->id)->get();
        } else {
            $shops = Shop::isActive()->get();
        }

        return view('admin.coupon.create', compact('discountTypes', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $coupon = CouponRepository::storeByRequest($request, null);

        $coupon->shops()->sync($request->shops);

        return to_route('admin.coupon.index')->withSuccess(__('Coupon created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $discountTypes = DiscountType::cases();
        $shops = Shop::isActive()->get();

        return view('admin.coupon.edit', compact('coupon', 'discountTypes', 'shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon = CouponRepository::updateByRequest($request, $coupon);

        $coupon->shops()->sync($request->shops);

        return to_route('admin.coupon.index')->withSuccess(__('Coupon updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->withSuccess(__('Coupon deleted successfully'));
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
