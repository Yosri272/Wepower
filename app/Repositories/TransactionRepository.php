<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class TransactionRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Transaction::class;
    }

    /**
     * transaction store
     */
    public static function storeByRequest(Wallet $wallet, $amount, $type, $hasAdminUpdate, $isCommission, $purpose = null, $description = null): Transaction
    {
        $transaction = self::create([
            'wallet_id' => $wallet->id,
            'transaction_id' => str_pad($wallet->transactions()->count() + 1, 6, '0', STR_PAD_LEFT),
            'amount' => $amount,
            'type' => $type,
            'is_commission' => $isCommission,
            'purpose' => $purpose,
            'note' => $description,
        ]);

        if ($type == 'credit') {
            $wallet->increment('balance', $amount);
        } else {
            $wallet->decrement('balance', $amount);
        }

        if ($hasAdminUpdate) {
            self::updateByAdminWallet($amount);
        }

        return $transaction;
    }

    public static function updateByAdminWallet($amount)
    {
        $roles = ['admin', 'root'];

        $users = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();

        foreach ($users as $user) {
            $user->wallet()->increment('balance', $amount);
        }

        return true;
    }
}
