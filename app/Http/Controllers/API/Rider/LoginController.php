<?php

namespace App\Http\Controllers\API\Rider;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreatePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RiderRequest;
use App\Http\Resources\RiderUserResource;
use App\Http\Resources\UserResource;
use App\Models\DeviceKey;
use App\Models\User;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\DriverRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Services\SMS;
use App\Services\SmsGatewayService;
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
    public function login(LoginRequest $request)
    {
        if ($user = $this->authenticate($request)) {
            if (!$user->is_active) {
                return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
            }

            if ($request->device_key && !$this->findByKey($request->device_key, $user)) {
                DeviceKey::create([
                    'user_id' => $user->id,
                    'key' => $request->device_key,
                    'device_type' => $request->device_type ?? 'android',
                ]);
            }

            return $this->json('Log In Successfull', [
                'user' => new UserResource($user),
                'access' => UserRepository::getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * register new user.
     *
     * @param  RiderRequest  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function register(RiderRequest $request)
    {
        $user = UserRepository::storeByRequest($request);
        $user->assignRole(Roles::DRIVER->value);

        DriverRepository::storeByUser($user);

        $user->update(['is_active' => false]);

        $verificationCode = VerificationCodeRepository::findOrCreateByContact($request->phone);

        $message = 'Hello Your Ridder Registration OTP is ' . $verificationCode->otp;
        (new SmsGatewayService())->sendSMS($request->phone, $message);

        return $this->json('Your profile is under review', [
            'user' => RiderUserResource::make($user),
        ]);
    }

    /**
     * Authenticate a user.
     */
    private function authenticate($request)
    {
        $user = UserRepository::findByContact($request->phone);
        if (!is_null($user) && $user->driver) {
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
        $user = User::find(auth()->user()->id);
        $currentPassword = $request->current_password;
        if (Hash::check($currentPassword, $user->password)) {

            if (Hash::check($request->password, $user->password)) {
                return $this->json('New password cannot be same as current password', [], Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password change successfully', [
                'user' => RiderUserResource::make($user),
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
    public function sendOTP(Request $request)
    {
        $forgotPassword = $request->forgot_password;

        $validate = $forgotPassword ? 'required|numeric|exists:users,phone' : 'required|numeric|unique:users,phone';

        $request->validate(['phone' => $validate]);

        $verificationCode = VerificationCodeRepository::findOrCreateByContact($request->phone);

        $message = 'Hello Your Ridder Registration OTP is ' . $verificationCode->otp;
        (new SmsGatewayService())->sendSMS($request->mobile, $message);

        return $this->json('Please Verify your phone number. We sent otp to your number', [
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
        $verificationCode = VerificationCodeRepository::checkOTP($request->phone, $request->otp);

        if (!$verificationCode) {
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
    public function createPassword(CreatePasswordRequest $request)
    {
        $verifyOtp = VerificationCodeRepository::checkByToken($request->token);
        $user = UserRepository::query()->where('phone', $verifyOtp->phone)->first();

        if (!$user) {
            return $this->json('User not found', [], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyOtp->delete();

        return $this->json('Password created successfully', [
            'user' => RiderUserResource::make($user),
        ]);
    }
}
