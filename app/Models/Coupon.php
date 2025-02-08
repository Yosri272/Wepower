<?php

namespace App\Models;

use App\Enums\DiscountType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => DiscountType::class,
    ];

    /**
     * get shop model for this coupon.
     * */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * get shops model for this coupon.
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'admin_coupons', 'coupon_id', 'shop_id');
    }

    /**
     * get users model for this coupon.
     * */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coupon_collects', 'coupon_id', 'user_id');
    }

    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Check if the query is valid based on start and end dates.
     *
     * @param  datatype  $query  The query to be checked
     * @return mixed
     */
    public function scopeIsValid($query)
    {
        return $query->where('started_at', '<=', Carbon::now())->where('expired_at', '>=', Carbon::now());
    }
}
