<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShopOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the order that owns the ShopOrder.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Retrieve the products associated with the current model.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'color', 'unit', 'size');
    }

    /**
     * Get the shop that owns the ShopOrder.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
