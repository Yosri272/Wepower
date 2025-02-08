<?php

namespace App\Repositories;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Review::class;
    }

    /**
     * Store a new review based on the request data and product.
     *
     * @param  Product  $product  and ReviewRequest $request
     */
    public static function storeByRequest(ReviewRequest $request, Product $product): Review
    {
        return self::create([
            'customer_id' => auth()->user()->customer->id,
            'product_id' => $product->id,
            'shop_id' => $product->shop->id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'description' => $request->description,
        ]);
    }
}
