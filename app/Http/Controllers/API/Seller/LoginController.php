<?php

namespace App\Http\Controllers\API\Seller;

use App\Events\AdminProductRequestEvent;
use App\Events\SendOTPMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreatePasswordRequest;
use App\Http\Requests\EmailSendOTPRequest;
use App\Http\Requests\SellerLoginRequest;
use App\Http\Requests\ShopCreateRequest;
use App\Http\Resources\SellerUserResource;
use App\Models\DeviceKey;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ShopRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * check user status
     */
    public function checkUserStatus(Request $request)
    {
        $request->validate(['phone' => 'nullable|numeric|exists:users,phone']);

        $user = UserRepository::findByContact($request->phone);

        $message = $user?->is_active ? 'Your account is active' : 'Your account is not active. please contact the admin';

        return $this->json($message, [
            'user_status' => (bool) $user?->is_active ?? false,
        ]);
    }

    /**
     * login a user.
     */
    public function login(SellerLoginRequest $request)
    {
        if ($user = $this->authenticate($request)) {
            if (! $user->is_active) {
                return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
            }

            if ($request->device_key && ! $this->findByKey($request->device_key, $user)) {
                DeviceKey::create([
                    'user_id' => $user->id,
                    'key' => $request->device_key,
                    'device_type' => $request->device_type ?? 'android',
                ]);
            }

            return $this->json('Log In Successfull', [
                'user' => SellerUserResource::make($user),
                'access' => UserRepository::getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * register new user.
     *
     * @param  ShopCreateRequest  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function register(ShopCreateRequest $request)
    {
        $shop = ShopRepository::storeByRequest($request);

        $shop->user()->update(['is_active' => false]);

        $message = 'New Shop Created Request';
        try {
            AdminProductRequestEvent::dispatch($message);
        } catch (\Exception $e) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'New Shop created for review. shop name: '.$shop->name,
            'url' => '/admin/shops/'.$shop->id,
            'icon' => 'bi-shop',
            'type' => 'success',
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return $this->json('Your profile is under review', [
            'user' => SellerUserResource::make($shop->user),
        ]);
    }

    /**
     * Authenticate a user.
     */
    private function authenticate($request)
    {
        $user = UserRepository::findByContact($request->contact);
        if (! is_null($user) && $user->shop) {
            if (Hash::check($request->password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user by device key.
     */
    public function findByKey($key, $user)
    {
        return DeviceKey::where('key', $key)->where('user_id', $user->id)->first();
    }

    /**
     * change password.
     *
     * @param  ChangePasswordRequest  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $currentPassword = $request->current_password;
        if (Hash::check($currentPassword, $user->password)) {

            if (Hash::check($request->password, $user->password)) {
                return $this->json('New password cannot be same as current password', [], Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password change successfully', [
                'user' => SellerUserResource::make($user),
            ]);
        }

        return $this->json('Current password is incorrect', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * logout a user.
     *
     * @param  Request  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        if ($request->device_key) {
            DeviceKeyRepository::deleteByKey($request->device_key, $user);
        }

        if ($user) {
            $user->currentAccessToken()->delete();

            return $this->json('Logged out successfully!');
        }

        return $this->json('No Logged in user found', [], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * send OTP
     *
     * @param  Request  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function sendOTP(EmailSendOTPRequest $request)
    {
        $verificationCode = VerificationCodeRepository::findOrCreateByContact($request->email);

        $type = $request->forgot_password ? 'Forgot Password' : 'Registration';

        $message = 'Hello Your Seller '.$type.' OTP is '.$verificationCode->otp;

        try {
            SendOTPMail::dispatch($request->email, $message);
        }catch(\Exception $e) {}

        return $this->json('Please Verify your email. We sent otp to your email address', [
            'otp' => $verificationCode->otp,
        ]);
    }

    /**
     * verify OTP
     *
     * @param  Request  $request  The request object containing the user data.
     */
    public function verifyOtp(Request $request)
    {
        $verificationCode = VerificationCodeRepository::checkOTP($request->email, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid OTP', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Otp matched successfully!', [
            'token' => $verificationCode->token,
        ]);
    }

    /**
     * create new password
     *
     * @param  CreatePasswordRequest  $request  The request object containing the user data.
     */
    public function forgotPassword(CreatePasswordRequest $request)
    {
        $verifyOtp = VerificationCodeRepository::checkByToken($request->token);
        $user = UserRepository::query()->where('phone', $verifyOtp->phone)->orWhere('email', $verifyOtp->phone)->first();

        if (! $user) {
            return $this->json('User not found', [], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyOtp->delete();

        return $this->json('Password changes successfully', [
            'user' => SellerUserResource::make($user),
        ]);
    }
}
