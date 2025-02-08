<?php

namespace App\Http\Controllers\Gateway\PayStack;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Yabacon\Paystack;

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
        $paystack = new Paystack($config->secret_key);

        try {
            // Initialize transaction with success and cancel URLs
            $transaction = $paystack->transaction->initialize([
                'amount' => $payment->amount * 100,  // Amount in kobo (e.g., 10000 for ₦100.00)
                'email' => 'customer@example.com',
                'callback_url' => route('payment.success', $payment->id),
                'cancel_url' => route('payment.cancel', $payment->id),
                'metadata' => [
                    'custom_fields' => [
                        [
                            'display_name' => 'Customer Name',
                            'variable_name' => 'customer_name',
                            'value' => 'Total Orders ('.$payment->orders->count().')',
                        ],
                    ],
                ],
                // 'currency' => 'USD'
            ]);

            // Redirect user to payment page
            return $transaction->data->authorization_url;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
