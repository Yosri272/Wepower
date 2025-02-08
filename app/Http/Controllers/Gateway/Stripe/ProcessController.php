<?php

namespace App\Http\Controllers\Gateway\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ProcessController extends Controller
{
    /**
     * Process to stripe
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment)
    {
        $config = json_decode($paymentGateway->config);

        Stripe::setApiKey($config->secret_key);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'PaymentID #'.str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                            'metadata' => [
                                'order_ids' => $payment->orders->pluck('id')->implode(','),
                                'amount' => $payment->amount,
                                'total_orders' => $payment->orders->count(),
                            ],
                        ],
                        'unit_amount' => $payment->amount * 100, // Amount in cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success', $payment->id),
            'cancel_url' => route('payment.cancel', $payment->id),
        ]);

        return $session->url;
    }
}
