<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * get wallet model for this user.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'id', 'user_id');
    }

    /**
     * get withdraw model for this user.
     */
    public function withdraws(): HasMany
    {
        return $this->hasMany(Withdraw::class, 'id', 'user_id');
    }

    /**
     * get customer model for this user.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id', 'user_id');
    }

    /**
     * get coupons model for this user.
     * */
    public function coupons(): HasMany
    {
        return $this->hasMany(CouponCollect::class, 'user_id', 'id');
    }

    /**
     * get shop model for this user.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'id', 'user_id');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'user_id');
    }

    public function devices()
    {
        return $this->hasMany(DeviceKey::class);
    }

    public function viewProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'recent_views', 'user_id', 'product_id')->withTimestamps();
    }

    /**
     * get media model for this user.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Generate a thumbnail for the media, if available, or use the default image.
     */
    public function thumbnail(): Attribute
    {
        $thumbnail = asset('defualt/profile.jpg');
        if ($this->media && Storage::exists($this->media->src)) {
            $thumbnail = Storage::url($this->media->src);
        }

        return Attribute::make(
            get: fn () => $thumbnail
        );
    }

    /**
     * Scope a query to only include active records.
     *
     * @param  mixed  $query
     * @return mixed
     */
    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * get full name of the user.
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name.' '.$this->last_name
        );
    }
}
