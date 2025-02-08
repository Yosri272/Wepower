<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Retrieve a paginated list of reviews based on the provided request parameters.
     *
     * @param  Request  $request  The request object containing page, per_page, product_id and shop_id parameters
     * @return Some_Return_Value The JSON response containing total and reviews data
     */
    public function index(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'shop_id' => 'nullable|exists:shops,id',
        ]);

        $productID = $request->product_id;
        $shopID = $request->shop_id;

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $reviews = ReviewRepository::query()
            ->when($productID, function ($query) use ($productID) {
                return $query->where('product_id', $productID);
            })
            ->when($shopID, function ($query) use ($shopID) {
                return $query->where('shop_id', $shopID);
            });

        $total = $reviews->count();

        $reviews = $reviews->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->get();

        $shopOrProduct = null;
        if ($request->shop_id) {
            $shopOrProduct = ShopRepository::findOrFail($request->shop_id);
        } elseif ($request->product_id) {
            $shopOrProduct = ProductRepository::findOrFail($request->product_id);
        }

        // request has shop or product
        $averageRatingAndPercentage = null;
        if ($shopOrProduct) {

            $totalReview = count($shopOrProduct->reviews);
            $averageRating = number_format($shopOrProduct->averageRating, 1, '.', '');

            // Calculate the rating percentage
            $ratingOne = $shopOrProduct->reviews()->whereBetween('rating', [1.0, 1.9])->count();
            $ratingTwo = $shopOrProduct->reviews()->whereBetween('rating', [2.0, 2.9])->count();
            $ratingThree = $shopOrProduct->reviews()->whereBetween('rating', [3.0, 3.9])->count();
            $ratingFour = $shopOrProduct->reviews()->whereBetween('rating', [4.0, 4.9])->count();
            $ratingFive = $shopOrProduct->reviews()->where('rating', 5)->count();

            // Calculate the percentage
            $percentageOne = $ratingOne ? (($ratingOne / $totalReview) * 100) : 0;
            $percentageTwo = $ratingTwo ? (($ratingTwo / $totalReview) * 100) : 0;
            $percentageThree = $ratingThree ? (($ratingThree / $totalReview) * 100) : 0;
            $percentageFour = $ratingFour ? (($ratingFour / $totalReview) * 100) : 0;
            $percentageFive = $ratingFive ? (($ratingFive / $totalReview) * 100) : 0;

            // array of the average rating and percentage
            $averageRatingAndPercentage = [
                'rating' => (float) $averageRating,
                'total_review' => (int) $totalReview,
                'percentages' => (array) [
                    '1' => (float) number_format($percentageOne, 2, '.', ''),
                    '2' => (float) number_format($percentageTwo, 2, '.', ''),
                    '3' => (float) number_format($percentageThree, 2, '.', ''),
                    '4' => (float) number_format($percentageFour, 2, '.', ''),
                    '5' => (float) number_format($percentageFive, 2, '.', ''),
                ],
            ];
        }

        return $this->json('reviews', [
            'average_rating_percentage' => $averageRatingAndPercentage,
            'total' => $total,
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }
}
