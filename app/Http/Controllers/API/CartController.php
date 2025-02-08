<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Requests\CheckoutRequest;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isBuyNow = request()->is_buy_now ?? false;

        $carts = auth()->user()->customer->carts()->where('is_buy_now', $isBuyNow)->get();
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('cart list', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;

        $product = ProductRepository::find($request->product_id);

        $quantity = $request->quantity ?? 1;

        $customer = auth()->user()->customer;
        $cart = $customer->carts()->where('product_id', $product->id)->first();

        if ($isBuyNow) {

            $buyNowCart = $customer->carts()->where('is_buy_now', true)->first();

            if ($buyNowCart && $buyNowCart->product_id != $request->product_id) {
                $buyNowCart->delete();
            }
        }

        if (($product->quantity < $quantity) || ($product->quantity <= $cart?->quantity)) {
            return $this->json('Sorry! product cart quantity is limited. No more stock', [], 422);
        }

        // store or update cart
        CartRepository::storeOrUpdateByRequest($request, $product);

        $carts = $customer->carts()->where('is_buy_now', $isBuyNow)->get();

        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('product added to cart', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }

    /**
     * increase cart quantity
     */
    public function increment(CartRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;

        $product = ProductRepository::find($request->product_id);

        $customer = auth()->user()->customer;

        $cart = $customer->carts()?->where('product_id', $product->id)->first();

        if (!$cart) {
            return $this->json('Sorry product not found in cart', [], 422);
        }

        $quantity = $cart->quantity;

        if ($product->quantity > $quantity) {
            $cart->update([
                'quantity' => $quantity + 1,
            ]);
        } else {
            return $this->json('Sorry! product cart quantity is limited. No more stock', [], 422);
        }

        $carts = $customer->carts()->where('is_buy_now', $isBuyNow)->get();
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('product quantity increased', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }

    /**
     * decrease cart quantity
     * */
    public function decrement(CartRequest $request)
    {

        $isBuyNow = $request->is_buy_now ?? false;

        $product = ProductRepository::find($request->product_id);
        $customer = auth()->user()->customer;
        $cart = $customer->carts()?->where('product_id', $product->id)->first();

        if (!$cart) {
            return $this->json('Sorry product not found in cart', [], 422);
        }

        $message = 'product removed from cart';

        if ($cart->quantity > 1) {
            $cart->update([
                'quantity' => $cart->quantity - 1,
            ]);

            $message = 'product quantity decreased';
        } else {
            $cart->delete();
        }

        $carts = $customer->carts()->where('is_buy_now', $isBuyNow)->get();
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json($message, [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }

    public function checkout(CheckoutRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;

        $shopIds = $request->shop_ids ?? [];
        $customer = auth()->user()->customer;

        $carts = $customer->carts()->whereIn('shop_id', $shopIds)->where('is_buy_now', $isBuyNow)->get();

        $checkout = CartRepository::checkoutByRequest($request, $carts);

        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        $message = 'Checkout information';

        $applyCoupon = false;

        if ($request->coupon_code && $checkout['coupon_discount'] > 0) {
            $applyCoupon = true;
            $message = 'Coupon applied';
        } elseif ($request->coupon_code) {
            $message = 'Coupon not applied';
        }

        return $this->json($message, [
            'checkout' => $checkout,
            'apply_coupon' => $applyCoupon,
            'checkout_items' => $shopWiseProducts,
        ]);
    }

    public function destroy(CartRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;

        $customer = auth()->user()->customer;

        $cart = $customer->carts()->where('product_id', $request->product_id)->first();

        if (!$cart) {
            return $this->json('Sorry product not found in cart', [], 422);
        }

        $cart->delete();

        $carts = $customer->carts()->where('is_buy_now', $isBuyNow)->get();
        $groupCart = $carts->groupBy('shop_id');
        $shopWiseProducts = CartRepository::ShopWiseCartProducts($groupCart);

        return $this->json('product removed from cart', [
            'total' => $carts->count(),
            'cart_items' => $shopWiseProducts,
        ], 200);
    }
}
