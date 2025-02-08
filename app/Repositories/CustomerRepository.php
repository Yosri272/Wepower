<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\User;

class CustomerRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Customer::class;
    }

    /**
     * Store customer by request.
     *
     * @param  User  $user  The user object
     */
    public static function storeByRequest(User $user): Customer
    {
        return self::create([
            'user_id' => $user->id,
        ]);
    }
}
