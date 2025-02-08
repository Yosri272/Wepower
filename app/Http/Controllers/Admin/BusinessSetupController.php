<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneraleSettingRequest;
use App\Models\GeneraleSetting;
use App\Repositories\GeneraleSettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BusinessSetupController extends Controller
{
    /**
     * Display a listing of the business settings.
     */
    public function index()
    {
        $timezones = [];
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $timezones[$key]['zone'] = $zone;
            $timezones[$key]['diff_from_GMT'] = 'UTC/GMT '.date('P', $timestamp);
        }

        return view('admin.business-setup.index', compact('timezones'));
    }

    /**
     * Update the business settings.
     */
    public function update(GeneraleSettingRequest $request)
    {
        if (app()->environment() == 'local') {
            return back()->with('demoMode', 'You can not update the business settings in demo mode');
        }

        GeneraleSettingRepository::updateOrCreateByRequest($request);

        if ($request->timezone != config('app.timezone')) {
            $this->setEnv('APP_TIMEZONE', $request->timezone);
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
        }

        return back()->withSuccess(__('Business settings updated successfully'));
    }

    /**
     * shop info settings
     */
    public function shop()
    {

        return view('admin.business-setup.shop');
    }

    /**
     * toggle pos
     */
    public function togglePOS()
    {
        $generaleSetting = GeneraleSetting::first();

        $generaleSetting?->update([
            'shop_pos' => ! $generaleSetting->shop_pos,
        ]);

        return back()->withSuccess(__('POS status updated successfully'));
    }

    /**
     * toggle register
     */
    public function toggleRegister()
    {
        $generaleSetting = GeneraleSetting::first();

        $generaleSetting->update([
            'shop_register' => ! $generaleSetting->shop_register,
        ]);

        return back()->withSuccess(__('Register status updated successfully'));
    }

    /**
     * update shop info
     */
    public function shopUpdate(Request $request)
    {
        if (app()->environment() == 'local') {
            return back()->with('demoMode', 'You can not update the shop settings in demo mode');
        }

        GeneraleSettingRepository::updateOrCreateShopSetting($request);

        return back()->withSuccess(__('Shop info updated successfully'));
    }

    /**
     * withdraw settings
     */
    public function withdraw()
    {
        return view('admin.business-setup.withdraw');
    }

    /**
     * update withdraw settings
     */
    public function withdrawUpdate(Request $request)
    {
        if (app()->environment() == 'local') {
            return back()->with('demoMode', 'You can not update the withdraw settings in demo mode');
        }
        GeneraleSettingRepository::updateOrCreateWithdrawSetting($request);

        return back()->withSuccess(__('Withdraw settings updated successfully'));
    }
}
