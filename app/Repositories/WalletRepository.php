<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;

class WalletRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Wallet::class;
    }

    /**
     * wallet store by request
     */
    public static function storeByRequest(User $user): Wallet
    {
        return self::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
    }

    /**
     * wallet update by request
     *
     * @param  float  $balence
     * @param  string  $type  (credit or debit)
     */
    public static function updateByRequest(Wallet $wallet, $balence, $type): Wallet
    {
        // banlence increase or decrease
        $ballence = $type == 'credit' ? $wallet->balance + $balence : $wallet->balance - $balence;

        $wallet->update([
            'balance' => $ballence,
        ]);

        return $wallet;
    }

    public static function getAdminWallet(): Wallet
    {
        $role = 'root';

        $user = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->first();

        return $user->wallet;
    }
}
