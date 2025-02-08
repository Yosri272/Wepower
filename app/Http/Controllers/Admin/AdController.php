<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Ad;
use App\Repositories\AdsRepository;

class AdController extends Controller
{
    /**
     * Display a listing of the ads.
     */
    public function index()
    {
        // Get ads
        $ads = Ad::query()->paginate(20);

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * create new ad
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * store a new ad
     */
    public function store(BannerRequest $request)
    {
        AdsRepository::storeByRequest($request);

        return to_route('admin.ad.index')->withSuccess('Ad created successfully');
    }

    /**
     * edit a ad
     */
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * update a banner
     */
    public function update(BannerRequest $request, Ad $ad)
    {
        AdsRepository::updateByRequest($request, $ad);

        return to_route('admin.ad.index')->withSuccess('Ad updated successfully');
    }

    /**
     * status toggle a ad
     */
    public function statusToggle(Ad $ad)
    {
        $ad->update([
            'status' => ! $ad->status,
        ]);

        return to_route('admin.ad.index')->withSuccess('Ad status updated');
    }

    /**
     * destroy a ad
     */
    public function destroy(Ad $ad)
    {
        // delete ad
        AdsRepository::destroy($ad);

        return to_route('admin.ad.index')->withSuccess('Ad deleted successfully');
    }
}
