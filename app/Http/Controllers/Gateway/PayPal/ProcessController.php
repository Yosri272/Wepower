<?php

namespace App\Http\Controllers\Gateway\PayPal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class ProcessController extends Controller
{
    /**
     * Process to PayPal
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment)
    {
        $config = json_decode($paymentGateway->config);

        $environment = $paymentGateway->mode === 'live'
            ? new ProductionEnvironment($config->client_id, $config->client_secret)
            : new SandboxEnvironment($config->client_id, $config->client_secret);

        $client = new PayPalHttpClient($environment);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $payment->amount,
                ],
            ]],
            'application_context' => [
                'return_url' => route('payment.success', $payment->id),
                'cancel_url' => route('payment.cancel', $payment->id),
            ],
        ];

        try {
            $response = $client->execute($request);

            return $response->result->links[1]->href; // Redirect to PayPal for payment approval
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
