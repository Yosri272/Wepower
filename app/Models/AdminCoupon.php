<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCoupon extends Model
{
    use HasFactory;

    /**
     * get coupon model for this coupon.
     * */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * get shop model for this coupon.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
