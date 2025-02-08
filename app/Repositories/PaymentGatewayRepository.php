<?php

namespace App\Repositories;

use App\Http\Requests\PaymentGatewayRequest;
use App\Models\PaymentGateway;

class PaymentGatewayRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return PaymentGateway::class;
    }

    /**
     * update payment gateway
     */
    public static function updateByRequest(PaymentGatewayRequest $request, PaymentGateway $paymentGateway): PaymentGateway
    {
        $config = json_encode($request->config);

        $media = $paymentGateway->media;

        if ($request->hasFile('logo') && ! $media) {
            $media = MediaRepository::storeByRequest($request->logo, 'gatewaylogo', 'image');
        }

        if ($request->hasFile('logo') && $media) {
            $media = MediaRepository::updateByRequest($request->logo, 'gatewaylogo', 'image', $media);
        }

        $paymentGateway->update([
            'mode' => $request->mode,
            'title' => $request->title,
            'media_id' => $media ? $media->id : null,
            'config' => $config,
        ]);

        return $paymentGateway;
    }
}
