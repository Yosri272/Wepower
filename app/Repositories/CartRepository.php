<?php

namespace App\Repositories;

use Abedin\Boiler\Repositories\Repository;
use App\Http\Requests\CartRequest;
use App\Http\Requests\GiftRequest;
use App\Http\Resources\AddressResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Number;

class CartRepository extends Repository
{
    public static function model()
    {
        return Cart::class;
    }

    public static function ShopWiseCartProducts($groupCart)
    {
        $shopWiseProducts = collect([]);
        foreach ($groupCart as $key => $products) {
            $productArray = collect([]);

            foreach ($products as $cart) {
                $discountPercentage = $cart->product->getDiscountPercentage($cart->product->price, $cart->product->discount_price);

                $totalSold = $cart->product->orders->sum('pivot.quantity');

                $gift = null;

                if ($cart->gift_id) {
                    $gift = [
                        'id' => $cart->gift_id,
                        'cart_id' => $cart->id,
                        'name' => $cart->gift->name,
                        'thumbnail' => $cart->gift->thumbnail,
                        'price' => (float) $cart->gift->price,
                        'receiver_name' => $cart->gift_receiver_name,
                        'sender_name' => $cart->gift_sender_name,
                        'note' => $cart->gift_note,
                        'address' => $cart->address ? AddressResource::make($cart->address) : null,
                    ];
                }

                $productArray[] = (object) [
                    'id' => $cart->product->id,
                    'quantity' => (int) $cart->quantity,
                    'name' => $cart->product->name,
                    'thumbnail' => $cart->product->thumbnail,
                    'brand' => $cart->product->brand?->name ?? null,
                    'price' => (float) $cart->product->price,
                    'discount_price' => (float) $cart->product->discount_price,
                    'discount_percentage' => (float) number_format($discountPercentage, 2, '.', ''),
                    'rating' => (float) $cart->product->averageRating,
                    'total_reviews' => (string) Number::abbreviate($cart->product->reviews->count(), maxPrecision: 2),
                    'total_sold' => (string) number_format($totalSold, 0, '.', ','),
                    'color' => $cart->color,
                    'size' => $cart->size,
                    'unit' => $cart->unit,
                    'gift' => $gift,
                ];
            }

            $shop = $products[0]->shop;
            $hasGift = $shop->gifts()->isActive()->count() > 0 ? true : false;
            $shopWiseProducts[] = (object) [
                'shop_id' => $key,
                'shop_name' => $shop->name,
                'shop_logo' => $shop->logo,
                'shop_rating' => (float) $shop->averageRating,
                'has_gift' => (bool) $hasGift,
                'products' => $productArray,

            ];
        }

        return $shopWiseProducts;
    }

    /**
     * Store or update cart by request.
     */
    public static function storeOrUpdateByRequest(CartRequest $request, Product $product): Cart
    {
        $size = $request->size ?? $product->sizes?->first()?->name;
        $color = $request->color ?? $product->colors?->first()?->name;
        $unit = $request->unit ?? $product->units?->first()?->name;

        $isBuyNow = $request->is_buy_now ?? false;

        $customer = auth()->user()->customer;

        $cart = $customer->carts()?->where('product_id', $product->id)->where('is_buy_now', $isBuyNow)->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + 1,
                'size' => $request->size ?? $cart->size,
                'color' => $request->color ?? $cart->color,
                'unit' => $request->unit ?? $cart->unit,
            ]);

            return $cart;
        }

        return self::create([
            'product_id' => $request->product_id,
            'shop_id' => $product->shop->id,
            'is_buy_now' => $isBuyNow,
            'customer_id' => $customer->id,
            'quantity' => $request->quantity ?? 1,
            'size' => $size,
            'color' => $color,
            'unit' => $unit,
        ]);
    }

    public static function checkoutByRequest($request, $carts)
    {
        $totalAmount = 0;
        $deliveryCharge = 0;
        $giftCharge = 0;
        $couponDiscount = 0;
        $payableAmount = 0;

        if (! $carts->isEmpty()) {

            foreach ($carts as $cart) {
                $price = $cart->product->discount_price > 0 ? $cart->product->discount_price : $cart->product->price;
                $totalAmount += $price * $cart->quantity;
                if ($cart->gift) {
                    $giftCharge += $cart->gift->price;
                }
            }

            $groupCarts = $carts->groupBy('shop_id');

            // get delivery charge
            $deliveryCharge = 0;
            foreach ($groupCarts as $shopId => $shopCarts) {

                $productQty = 0;

                foreach ($shopCarts as $cart) {
                    if ($cart->address && $cart->gift) {
                        $deliveryCharge += getDeliveryCharge($cart->quantity);
                    } else {
                        $productQty += $cart->quantity;
                    }
                }

                if ($productQty > 0) {
                    $deliveryCharge += getDeliveryCharge($productQty);
                }
            }

            // generate array for get discount
            $products = collect([]);
            foreach ($carts as $cart) {
                $products->push([
                    'id' => $cart->product_id,
                    'quantity' => (int) $cart->quantity,
                    'shop_id' => $cart->shop_id,
                ]);
            }
            $array = (object) [
                'coupon_code' => $request->coupon_code,
                'products' => $products,
            ];

            // get coupon discount
            $getDiscount = CouponRepository::getCouponDiscount($array);

            $couponDiscount = $getDiscount['discount_amount'];

            $totalAmount += $giftCharge;

            $payableAmount = $totalAmount + $deliveryCharge - $couponDiscount;
        }

        return [
            'total_amount' => (float) round($totalAmount, 2),
            'delivery_charge' => (float) round($deliveryCharge, 2),
            'coupon_discount' => (float) round($couponDiscount, 2),
            'payable_amount' => (float) round($payableAmount, 2),
            'gift_charge' => (float) round($giftCharge, 2),
        ];
    }

    public static function giftAddToCart(GiftRequest $request, Cart $cart): Cart
    {
        $cart->update([
            'gift_id' => $request->gift_id,
            'gift_receiver_name' => $request->receiver_name,
            'gift_sender_name' => $request->sender_name ?? auth()->user()->name,
            'gift_note' => $request->note,
            'address_id' => $request->address_id,
        ]);

        return $cart;
    }

    public static function giftDeleteToCart($request)
    {
        $cart = self::find($request->cart_id);

        if ($cart) {
            $cart->update([
                'gift_id' => null,
                'gift_receiver_name' => null,
                'gift_sender_name' => null,
                'gift_note' => null,
                'address_id' => null,
            ]);
        }

        return $cart;
    }
}
