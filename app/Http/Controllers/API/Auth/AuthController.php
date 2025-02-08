<?php

namespace App\Http\Controllers\API\Auth;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Repositories\WalletRepository;
use App\Services\SmsGatewayService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user and return the registration result.
     *
     * @param  RegistrationRequest  $request  The registration request data
     * @return Some_Return_Value The registration result data
     */
    public function register(RegistrationRequest $request)
    {
        // Create a new user
        $user = UserRepository::registerNewUser($request);

        if ($request->device_key) {
            DeviceKeyRepository::storeByRequest($user, $request);
        }

        // Create a new customer
        CustomerRepository::storeByRequest($user);

        //create wallet
        WalletRepository::storeByRequest($user);

        // Create a new verification code
        $verificationCode = VerificationCodeRepository::findOrCreateByContact($user->phone);

        $user->assignRole(Roles::CUSTOMER->value);

        $OTP = app()->environment('local') ? $verificationCode->otp : null;

        $message = 'Your Verification OTP is '.$verificationCode->otp;

        try {
            (new SmsGatewayService())->sendSMS($user->phone, $message);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->json('Registration successfully complete', [
            'user' => new UserResource($user),
            'access' => UserRepository::getAccessToken($user),
            'otp' => $OTP,
        ]);
    }

    /**
     * Login a user.
     *
     * @param  LoginRequest  $request  The login request data
     */
    public function login(LoginRequest $request)
    {
        // Authenticate the user
        $user = $this->authenticate($request);
        if ($user?->customer) {

            if ($request->device_key) {
                DeviceKeyRepository::storeByRequest($user, $request);
            }

            return $this->json('Login successfully', [
                'user' => new UserResource($user),
                'access' => UserRepository::getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Authenticate the user and return the user.
     *
     * @param  LoginRequest  $request  The login request
     * @return User|null
     */
    private function authenticate(LoginRequest $request)
    {
        $user = UserRepository::findByPhone($request->phone);
        if (! is_null($user) && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Logout the user and revoke the token.
     *
     * @model User $user
     *
     * @return string
     */
    public function logout()
    {
        $user = auth()->user();

        if ($user) {
            $user->currentAccessToken()->delete();

            return $this->json('Logged out successfully!');
        }

        return $this->json('User not found!', [], Response::HTTP_NOT_FOUND);
    }
}
