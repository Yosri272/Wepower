<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Scopes\PosOrderFalse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'order_status' => OrderStatus::class,
        'payment_method' => PaymentMethod::class,
        'payment_status' => PaymentStatus::class,
    ];

    /**
     * Get all of the products for the Order.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'color', 'unit', 'size', 'is_gift');
    }

    /**
     * Get the customer that owns the Order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the shop for the Order.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * Get the coupon for the Order.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id')->withTrashed();
    }

    /**
     * Get the address for the Order.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * Get the payments for the Order.
     */
    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'order_payments');
    }

    /**
     * Get the driver order for the Order.
     */
    public function driverOrder(): BelongsTo
    {
        return $this->belongsTo(DriverOrder::class, 'id', 'order_id');
    }

    /**
     * Get gift for the Order.
     */
    public function orderGift(): BelongsTo
    {
        return $this->belongsTo(OrderGift::class);
    }

    /**
     * apply global scope
     */
    protected static function booted()
    {
        static::addGlobalScope(new PosOrderFalse());
    }
}
