<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShopResource;
use App\Models\Ad;
use App\Repositories\BannerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Index method for retrieving banners, categories, and popular products.
     *
     * @return Some_Return_Value
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 8;
        $skip = ($page * $perPage) - $perPage;

        $banners = BannerRepository::query()->whereNull('shop_id')->active()->get();

        $categories = CategoryRepository::query()->active()
            ->whereHas('products', function ($query) {
                $query->whereHas('shop', function ($query) {
                    return $query->isActive();
                });
            })->withCount('products')->orderByDesc('products_count')
            ->take(10)->get();

        $popularProducts = ProductRepository::query()->isActive()->whereHas('orders')
            ->withCount('orders as orders_count')
            ->withAvg('reviews as average_rating', 'rating')
            ->orderByDesc('average_rating')
            ->orderByDesc('orders_count')
            ->take(6)->get();

        $justForYou = ProductRepository::query()->isActive()->latest('id');
        $total = $justForYou->count();
        $justForYou = $justForYou->skip($skip)->take($perPage)->get();

        $shops = ShopRepository::query()->isActive()->whereHas('products', function ($query) {
            return $query->isActive();
        })->withCount('orders')->withAvg('reviews as average_rating', 'rating')->orderByDesc('average_rating')->orderByDesc('orders_count')->take(8)->get();

        $ads = Ad::where('status', 1)->latest('id')->take(2)->get();

        return $this->json('home', [
            'banners' => BannerResource::collection($banners),
            'ads' => BannerResource::collection($ads),
            'categories' => CategoryResource::collection($categories),
            'shops' => ShopResource::collection($shops),
            'popular_products' => ProductResource::collection($popularProducts),
            'just_for_you' => [
                'total' => $total,
                'products' => ProductResource::collection($justForYou),
            ],
        ]);
    }

    public function recentlyViews()
    {
        $products = auth()->user()->viewProducts()->orderBy('pivot_updated_at', 'desc')->take(10)->get();

        return $this->json('recently viewed products', [
            'products' => ProductResource::collection($products),
        ]);
    }
}
