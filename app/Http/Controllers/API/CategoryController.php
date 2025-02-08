<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Retrieves a paginated list of categories with their associated products.
     *
     * @param  Request  $request  The HTTP request object.
     * @return JsonResponse The JSON response containing the categories and the total count.
     */
    public function index(Request $request)
    {
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $categories = CategoryRepository::query()->active()->whereHas('products', function ($query) {
            $query->whereHas('shop', function ($query) {
                return $query->isActive();
            });
        })->latest('id');
        $total = $categories->count();
        $categories = $categories->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->with('subCategories')->get();

        return $this->json('categories', [
            'total' => $total,
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Retrieves and displays the products of a specific category.
     *
     * @param  int  $id  The ID of the category.
     * @param  Request  $request  The HTTP request object.
     * @return JsonResponse The JSON response containing the category products.
     *
     * @throws None
     */
    public function show(Request $request)
    {
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $search = $request->search;
        $shopID = $request->shop_id;
        $categoryID = $request->category_id;
        $subCategoryID = $request->sub_category_id;

        $rating = $request->rating; //4.0
        $sortType = $request->sort_type;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;

        $category = $categoryID ? CategoryRepository::find($categoryID) : null;

        $products = ProductRepository::query()
            ->withCount('orders as orders_count')
            ->withAvg('reviews as average_rating', 'rating')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%'.$search.'%');
            })->when($shopID, function ($query) use ($shopID) {
                return $query->where('shop_id', $shopID);
            })->when($categoryID, function ($query) use ($categoryID) {
                $query->whereHas('categories', function ($query) use ($categoryID) {
                    return $query->where('id', $categoryID);
                });
            })->when($subCategoryID, function ($query) use ($subCategoryID) {
                $query->whereHas('subcategories', function ($query) use ($subCategoryID) {
                    return $query->where('id', $subCategoryID);
                });
            })->when($minPrice, function ($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            })->when($maxPrice, function ($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })->when($sortType == 'heigh_to_low', function ($query) {
                $query->orderBy('price', 'desc');
            })->when($sortType == 'low_to_high', function ($query) {
                $query->orderBy('price', 'asc');
            })->when($rating, function ($query) use ($rating) {
                $ratingValue = floatval($rating);
                $upperBound = $ratingValue + 1;

                return $query->havingRaw('average_rating >= '.$rating.' AND average_rating < '.$upperBound);
            })->when($sortType == 'top_selling', function ($query) {
                return $query->orderByDesc('orders_count');
            })->when($sortType == 'popular_product', function ($query) {
                return $query->orderByDesc('orders_count')->orderByDesc('average_rating');
            })->when($sortType == 'newest' || $sortType == 'just_for_you', function ($query) {
                return $query->orderBy('id', 'desc');
            })->isActive();

        $total = $products->count();
        $products = $products->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->get();

        return $this->json('Category products', [
            'total' => $total,
            'category' => CategoryResource::make($category),
            'products' => ProductResource::collection($products),
        ]);
    }
}
