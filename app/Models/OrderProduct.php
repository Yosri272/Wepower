<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shopOrder(): HasOne
    {
        return $this->hasOne(ShopOrder::class, 'shop_order_id');
    }
}
