<?php

namespace App\Repositories;

use App\Models\VerifyOtp;

class VerificationCodeRepository extends Repository
{
    /**
     * Get the model associated with the repository.
     *
     * @return string
     */
    public static function model()
    {
        return VerifyOtp::class;
    }

    /**
     * Find or create a VerifyOtp instance by the given phone number.
     *
     * @throws Some_Exception_Class description of exception
     */
    public static function findOrCreateByContact($phone): VerifyOtp
    {
        return self::query()->updateOrCreate(
            ['phone' => $phone],
            [
                'otp' => self::generateUniqueOtp(),
                'token' => self::generateUniqueToken(),
            ]
        );
    }

    /**
     * Check the OTP for a given phone number.
     */
    public static function checkOTP($phone, $otp): ?VerifyOtp
    {
        return self::query()->where(['phone' => $phone, 'otp' => $otp])
            ->latest()
            ->first();
    }

    /**
     * Checks the database for a record with the given token.
     */
    public static function checkByToken($token)
    {
        return self::query()->where('token', $token)->latest()->first();
    }

    /**
     * Generates a unique one-time password (OTP) and ensures its uniqueness.
     */
    private static function generateUniqueOtp(): int
    {
        do {
            $otp = mt_rand(1000, 9999);
        } while (self::query()->where('otp', $otp)->exists());

        return $otp;
    }

    /**
     * Generates a unique token by repeatedly calling generateToken until a unique token is found.
     *
     * @return string
     *
     * @throws Some_Exception_Class description of exception
     */
    private static function generateUniqueToken()
    {
        do {
            $token = self::generateToken();
        } while (self::query()->where('token', $token)->exists());

        return $token;
    }

    /**
     * Generates a token using hash_hmac with sha256 algorithm.
     *
     * @return string
     */
    private static function generateToken()
    {
        return hash_hmac(
            'sha256',
            uniqid(rand(100000000, 100000000000000), true),
            substr(hash('sha256', mt_rand()), 0, 32)
        );
    }
}
