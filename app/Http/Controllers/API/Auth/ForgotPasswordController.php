<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OTPRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Services\SmsGatewayService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Resend OTP to the user's phone.
     *
     * @param  OTPRequest  $request  description
     * @return json description
     */
    public function resendOTP(OTPRequest $request)
    {
        $user = UserRepository::findByPhone($request->phone);
        if ($user) {
            $verificationCode = VerificationCodeRepository::findOrCreateByContact($user->phone);

            $message = 'Hello Verification OTP is '.$verificationCode->otp;
            try {
                $response = (new SmsGatewayService())->sendSMS($user->phone, $message);
            } catch (\Exception $e) {
            }

            return $this->json('Verification code is sent to your phone', [
                'otp' => $verificationCode->otp,
            ]);
        }

        return $this->json('Sorry, your account is not active', [], 422);
    }

    /**
     * Verify the OTP for the user.
     *
     * @param  OTPVerifyRequest  $request  the request containing the OTP to be verified
     */
    public function verifyOtp(OTPVerifyRequest $request)
    {
        $user = UserRepository::findByPhone($request->phone);

        $verificationCode = VerificationCodeRepository::checkOTP($user->phone, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid otp', [], Response::HTTP_BAD_REQUEST);
        }

        if (! $user->phone_verified_at) {
            $user->update(['phone_verified_at' => now()]);
        }

        return $this->json('Otp verified successfully', [
            'token' => $verificationCode->token,
        ]);
    }

    /**
     * Reset the user's password.
     *
     * @param  PasswordResetRequest  $request  The request containing the password reset data
     */
    public function resetPassword(PasswordResetRequest $request)
    {
        $verifyOTP = VerificationCodeRepository::checkByToken($request->token);

        $user = UserRepository::findByPhone($verifyOTP->phone);

        if (! $user) {
            return $this->json('Sorry! No user found with this phone.', [], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyOTP->delete();

        return $this->json('Password reset successfully');
    }
}
