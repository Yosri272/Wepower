<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Repositories\BannerRepository;

class BannerController extends Controller
{
    /**
     * Get all banners
     */
    public function index()
    {
        $banners = BannerRepository::query()->whereNull('shop_id')->active()->get();

        return $this->json('all banners', [
            'banners' => BannerResource::collection($banners),
        ]);
    }
}
