<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * get user model for this wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get transaction model for this wallet.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id');
    }
}
