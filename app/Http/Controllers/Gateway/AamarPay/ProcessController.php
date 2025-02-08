<?php

namespace App\Http\Controllers\Gateway\AamarPay;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class ProcessController extends Controller
{
    /**
     * Process to aamarPay
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment)
    {
        $config = json_decode($paymentGateway->config);

        $customerEmail = $payment->orders[0]?->customer?->user?->email ?? 'example@gmail.com';
        $customerPhone = $payment->orders[0]?->customer?->user?->phone ?? '01870******';
        $customerName = $payment->orders[0]?->customer?->user?->name ?? 'Example Name';

        $endPoint = $paymentGateway->mode == 'live' ? 'https://secure.aamarpay.com/index.php' : 'https://sandbox.aamarpay.com/index.php';

        $description = 'Total order '.$payment->orders->count().' total amount '.$payment->amount.'BDT '.' orderIDs '.implode(',', $payment->orders->pluck('id')->toArray());

        $transitionID = str_pad($payment->id, 6, '0', STR_PAD_LEFT);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'store_id' => $config->store_id,
                'signature_key' => $config->signature_key,
                'cus_name' => $customerName,
                'cus_email' => $customerEmail,
                'cus_phone' => $customerPhone,
                'amount' => $payment->amount,
                'currency' => 'BDT',
                'tran_id' => $transitionID,
                'desc' => $description,
                'success_url' => route('payment.success', $payment->id),
                'fail_url' => route('payment.cancel', $payment->id),
                'cancel_url' => route('payment.cancel', $payment->id),
                'type' => 'json',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if (isset($response->payment_url)) {
            return $response->payment_url;
        }

        return json_encode(['error' => $response]);
    }
}
