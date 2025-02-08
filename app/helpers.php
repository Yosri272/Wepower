<?php

use App\Models\DeliveryCharge;
use App\Models\GeneraleSetting;

if (! function_exists('getDistance')) {
    /**
     * Calculate the distance between two geographical coordinates.
     *
     * @param  array  $firstLatLng  The first [latitude, longitude] coordinates
     * @param  array  $secondLatLng  The second [latitude, longitude] coordinates
     * @return float The distance between the two coordinates in kilometers
     */
    function getDistance(array $firstLatLng, array $secondLatLng): float
    {
        if (empty($firstLatLng) || empty($secondLatLng)) {
            return 0;
        }

        $theta = ($firstLatLng[1] - $secondLatLng[1]);
        $dist = sin(deg2rad($firstLatLng[0])) *
            sin(deg2rad($secondLatLng[0])) +
            cos(deg2rad($firstLatLng[0])) *
            cos(deg2rad($secondLatLng[0])) *
            cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles * 1.609344;
    }
}

if (! function_exists('showCurrency')) {

    /**
     * Show the currency in the given amount.
     *
     * @param  float  $amount
     */
    function showCurrency($amount = null): string
    {
        $generaleSetting = GeneraleSetting::first();

        $currency = $generaleSetting?->currency ?? '$';

        $amount = ($amount == 0 || $amount == null) ? 0 : $amount;

        if ($generaleSetting?->currency_position == 'suffix') {
            return $amount.$currency;
        }

        return $currency.$amount;
    }
}

if (! function_exists('getDeliveryCharge')) {

    /**
     * get the delivery charge.
     *
     * @param  int  $orderQuantity
     */
    function getDeliveryCharge($orderQuantity): float
    {
        $deliveryCharge = DeliveryCharge::where('min_qty', '<=', $orderQuantity)
            ->where('max_qty', '>=', $orderQuantity)
            ->first();

        return $deliveryCharge?->charge ?? 0;
    }
}
