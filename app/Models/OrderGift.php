<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderGift extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
