<?php

namespace App\Http\Controllers\Gateway;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Payment gateway
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function payment(Payment $payment, Request $request)
    {
        $geteway = $request->gateway;
        $amount = $payment->amount;

        if ($payment->is_paid) {
            return to_route('order.payment.cancel', ['payment' => $payment, 'error' => 'Order already paid']);
        }

        $paymentGateway = PaymentGateway::where('name', $geteway)->first();

        if (! $paymentGateway || ! $paymentGateway->is_active) {
            $message = $paymentGateway ? 'Payment gateway not active' : 'Payment gateway not found';

            return to_route('order.payment.cancel', ['payment' => $payment, 'error' => $message]);
        }

        $dirName = $paymentGateway->alias;

        $controller = __NAMESPACE__.'\\'.$dirName.'\\ProcessController';

        $url = $controller::process($paymentGateway, $payment, $amount);

        $error = json_decode($url);
        if ($error) {
            $error = $error->error ?? 'Payment gateway error';

            return to_route('order.payment.cancel', ['payment' => $payment, 'error' => $error]);
        }

        return redirect()->away($url);
    }

    /**
     * Payment success
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Payment $payment)
    {
        $payment->orders()->update([
            'payment_status' => PaymentStatus::PAID->value,
        ]);

        $payment->update([
            'is_paid' => true,
        ]);

        return to_route('order.payment.success', $payment);
    }

    /**
     * Payment cancel
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Payment $payment)
    {
        return to_route('order.payment.cancel', $payment);
    }

    /**
     * Payment success response show
     *
     * @return \Illuminate\Http\JsonResponse1`
     */
    public function paymentSuccess(Payment $payment)
    {

        // return $this->json('Order payment successfully', [
        //     'payment' => [
        //         'payment_status' => $payment->is_paid ? 'Paid' : 'Pending',
        //         'payment_method' => $payment->payment_method,
        //         'amount' => $payment->amount,
        //         'total_orders' => $payment->orders->count(),
        //     ],
        // ], 201);
    }

    /**
     * Payment cancel response show
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentCancel(Payment $payment, Request $request)
    {

        // return $this->json($request->error ?? 'Order payment cancelled', [
        //     'payment' => [
        //         'payment_status' => $payment->is_paid ? 'Paid' : 'Pending',
        //         'payment_method' => $payment->payment_method,
        //         'amount' => $payment->amount,
        //         'total_orders' => $payment->orders->count(),
        //     ],
        // ], 422);
    }
}
