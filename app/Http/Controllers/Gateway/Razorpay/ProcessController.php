<?php

namespace App\Http\Controllers\Gateway\Razorpay;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Razorpay\Api\Api;

class ProcessController extends Controller
{
    /**
     * Process to Razorpay
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment)
    {
        $config = json_decode($paymentGateway->config);

        $razorpay = new Api($config->key, $config->secret);

        $amount = (float) $payment->amount;
        $currency = 'INR';
        $receipt = 'payment_receipt_'.$payment->id;

        $successUrl = route('payment.success', $payment->id);
        $cancelUrl = route('payment.cancel', $payment->id);

        try {

            $name = $payment->orders[0]->customer?->user?->name ?? '';
            $email = $payment->orders[0]->customer?->user?->email ?? '';
            $phone = $payment->orders[0]->customer?->user?->phone ?? '';

            $description = 'Total order '.$payment->orders->count().' total amount '.$payment->amount.'INR';

            $paymentLink = $razorpay->invoice->create([
                'type' => 'link',
                'amount' => $amount * 100, // amount in paisa
                'currency' => $currency,
                'description' => $description,
                'customer' => [
                    'name' => $name,
                    'email' => $email,
                    'contact' => $phone,
                ],
                'callback_url' => $successUrl,
                'redirect' => true,
                'callback_method' => 'get',
                'cancel_url' => $cancelUrl,
            ]);

            return $paymentLink['short_url'];

        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}
