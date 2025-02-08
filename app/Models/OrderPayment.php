<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    /**
     * get the order that owns the OrderPayment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * get the payment that owns the OrderPayment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
