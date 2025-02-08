<?php

namespace App\Http\Controllers\Gateway\PayTabs;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class ProcessController extends Controller
{
    /**
     * Process to Paystack
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment)
    {
        $config = json_decode($paymentGateway->config);

        $name = $payment->orders[0]->customer?->user?->name ?? 'Not Available';
        $email = $payment->orders[0]->customer?->user?->email ?? 'Not Available';
        $phone = $payment->orders[0]->customer?->user?->phone ?? '0000000000';

        $params = [
            'profile_id' => $config->profile_id,
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'cart_currency' => $config->currency ?? 'USD',
            'cart_amount' => $payment->amount,
            'hide_shipping' => true,
            'cart_description' => 'items',
            'paypage_lang' => 'en',
            'callback' => route('paytabs.payment.callback', $payment->id),
            'return' => route('paytabs.payment.callback', $payment->id),
            'customer_ref' => 'test', //convert to string
            'customer_details' => [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'street1' => 'Not Available',
                'city' => 'Not Available',
                'state' => 'Not Available',
                'country' => 'Not Available',
                'zip' => '00000',
            ],
            'valu_down_payment' => '0',
            'tokenise' => 1,
        ];

        $baseUrl = $config->base_url ?? 'https://secure-global.paytabs.com';

        try {

            $response = Http::withHeaders([
                'Authorization' => $config->server_key,
                'Content-Type' => 'application/json',
            ])->post($baseUrl.'/payment/request', $params);

            $payment->update(['payment_token' => $response['tran_ref']]);

            return $response['redirect_url'];

        } catch (\Throwable $th) {
            return json_encode(['error' => $th->getMessage()]);
        }
    }

    public function callback(Payment $payment)
    {
        $paymentGateway = PaymentGateway::where('name', 'paytabs')->first();

        $config = json_decode($paymentGateway->config);

        $baseUrl = $config->base_url ?? 'https://secure-global.paytabs.com';

        $response = Http::withHeaders([
            'Authorization' => $config->server_key,
            'Content-Type' => 'application/json',
        ])->post($baseUrl.'/payment/query', [
            'profile_id' => $config->profile_id,
            'tran_ref' => $payment->payment_token,
        ])->json();

        if (isset($response['payment_result']['response_status']) && $response['payment_result']['response_status'] == 'A') {
            return to_route('payment.success', ['payment' => $payment]);
        } else {
            return to_route('order.payment.cancel', ['payment' => $payment, 'error' => isset($response['payment_result']['response_message']) ?? 'Payment failed']);
        }
    }
}
