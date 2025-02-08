<?php

namespace App\Http\Controllers\API\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Requests\BannerUpdateRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\SellerUserResource;
use App\Models\Banner;
use App\Repositories\BannerRepository;

class BannerController extends Controller
{
    public function index()
    {
        $banners = auth()->user()->shop->banners;

        return $this->json('all banners', [
            'banners' => BannerResource::collection($banners),
        ]);
    }

    public function store(BannerRequest $request)
    {
        BannerRepository::storeByRequest($request);

        return $this->json('banner created successfully', [
            'user' => SellerUserResource::make(auth()->user()),
        ]);
    }

    public function update(BannerUpdateRequest $request)
    {
        $banner = Banner::find($request->id);
        BannerRepository::updateByRequest($request, $banner);

        return $this->json('banner updated successfully', [
            'user' => SellerUserResource::make(auth()->user()),
        ]);
    }

    public function destroy(Banner $banner)
    {
        BannerRepository::destroy($banner);

        return $this->json('banner deleted successfully', [
            'user' => SellerUserResource::make(auth()->user()),
        ]);
    }
}
