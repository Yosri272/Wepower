<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiftRequest;
use App\Http\Resources\GiftResource;
use App\Repositories\CartRepository;
use App\Repositories\GiftRepository;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
        ]);

        // active gifts
        $gifts = GiftRepository::query()->whereShopId($request->shop_id)->isActive()->get();

        return $this->json('All gifts', [
            'gifts' => GiftResource::collection($gifts),
        ]);
    }

    public function store(GiftRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;

        $customer = auth()->user()->customer;

        $cart = $customer->carts()?->where('product_id', $request->product_id)->where('is_buy_now', $isBuyNow)->first();

        if (! $cart) {
            return $this->json('Sorry this product is not in cart', [], 422);
        }

        CartRepository::giftAddToCart($request, $cart);

        $carts = $customer->carts()->where('is_buy_now', $isBuyNow)->get();
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('Gift added to cart', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }

    public function update(GiftRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;

        $customer = auth()->user()->customer;

        $cart = $customer->carts()?->where('product_id', $request->product_id)->where('is_buy_now', $isBuyNow)->first();

        if (! $cart) {
            return $this->json('Sorry this product is not in cart', [], 422);
        }

        CartRepository::giftAddToCart($request, $cart);

        $carts = $customer->carts;
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('Gift updated to cart', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        $isBuyNow = $request->is_buy_now ?? false;

        CartRepository::giftDeleteToCart($request);

        $carts = auth()->user()->customer->carts()->where('is_buy_now', $isBuyNow)->get();
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('Gift deleted from cart', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }
}
