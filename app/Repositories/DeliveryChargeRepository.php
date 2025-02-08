<?php

namespace App\Repositories;

use Abedin\Boiler\Repositories\Repository;
use App\Http\Requests\DeliveryChargeRequest;
use App\Models\DeliveryCharge;

class DeliveryChargeRepository extends Repository
{
    public static function model()
    {
        return DeliveryCharge::class;
    }

    public static function storeByRequest(DeliveryChargeRequest $request): DeliveryCharge
    {
        return self::create([
            'charge' => $request->delivery_charge,
            'min_qty' => $request->min_order_qty,
            'max_qty' => $request->max_order_qty,
        ]);
    }

    public static function updateByRequest(DeliveryChargeRequest $request, DeliveryCharge $deliveryCharge): DeliveryCharge
    {
        $deliveryCharge->update([
            'charge' => $request->delivery_charge,
            'min_qty' => $request->min_order_qty,
            'max_qty' => $request->max_order_qty,
        ]);

        return $deliveryCharge;
    }
}
