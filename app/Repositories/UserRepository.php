<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{
    /**
     * Get the model.
     *
     * @return string The model class.
     */
    public static function model()
    {
        return User::class;
    }

    /**
     * Find a record by phone number.
     *
     * @param  datatype  $phone  description
     * @return Some_Return_Value
     */
    public static function findByPhone($phone)
    {
        return self::query()->where('phone', $phone)->orWhere('email', $phone)->isActive()->first();
    }

    public static function findByContact($contact)
    {
        return self::query()->where('phone', $contact)
            ->orWhere('email', $contact)
            ->first();
    }

    /**
     * Register a new user.
     *
     * @param  Request  $request  The request object
     */
    public static function registerNewUser(Request $request): User
    {
        $thumbnail = null;
        if ($request->hasFile('profile_photo')) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->profile_photo,
                'users/profile',
            );
        }

        return self::create([
            'name' => $request->first_name ?? $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth ?? null,
            'is_active' => true,
        ]);
    }

    public static function storeByRequest($request): User
    {
        $thumbnail = null;
        if ($request->hasFile('profile_photo')) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->profile_photo,
                'users/profile',
                'image'
            );
        }

        return self::create([
            'name' => $request->first_name ?? $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => Hash::make($request->password ?? $request->phone),
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'driving_lience' => $request->driving_lience,
            'date_of_birth' => $request->date_of_birth,
            'vehicle_type' => $request->vehicle_type,
            'is_active' => $request->is_active ? true : false,
        ]);
    }

    /**
     * Get the access token for the user.
     *
     * @param  User  $user  The user for whom the token is being obtained
     * @return array
     */
    public static function getAccessToken(User $user)
    {
        // $token = $user->createToken('user token');
        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'auth_type' => 'Bearer',
            'token' => $token,
            'expires_at' => now()->addDays(30)->toDateTimeString(),
        ];
    }

    /**
     * Update user by request.
     *
     * @param  $request  The user request
     * @param  mixed  $user  The user
     */
    public static function updateByRequest($request, $user): User
    {
        $thumbnail = self::updateProfilePhoto($request, $user);
        $name = $request->name ?? $request->first_name;
        $user->update([
            'name' => $name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth ? Carbon::parse($request->date_of_birth)->format('Y-m-d') : $user->date_of_birth,
            'driving_lience' => $request->driving_lience,
            'vehicle_type' => $request->vehicle_type,
        ]);

        return $user;
    }

    /**
     * Update the user's profile photo.
     */
    private static function updateProfilePhoto($request, $user)
    {
        $thumbnail = $user->media;
        if ($request->hasFile('profile_photo') && $thumbnail == null) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->profile_photo,
                'users/profile',
            );
        }

        if ($request->hasFile('profile_photo') && $thumbnail) {
            $thumbnail = MediaRepository::updateByRequest(
                $request->profile_photo,
                'users/profile',
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }
}
