<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, (new DriverOrder())->getTable())->withPivot('is_completed', 'assign_for', 'is_accept', 'cash_collect', 'driver_id', 'order_id', 'created_at', 'updated_at');
    }

    public function driverOrders(): HasMany
    {
        return $this->hasMany(DriverOrder::class, 'driver_id');
    }

    public function incompleteOrders()
    {
        return $this->orders()->whereHas('driverOrder', function ($query) {
            return $query->where('is_completed', 0);
        })->get();
    }

    // --------- scope ---------------
    public function scopeIsApprove(Builder $builder, $isApprove = true): Builder
    {
        return $builder->where('is_approve', $isApprove);
    }
}
