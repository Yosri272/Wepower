<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the shop user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get withdraw model for this user.
     */
    public function withdraws(): HasMany
    {
        return $this->hasMany(Withdraw::class, 'shop_id');
    }

    /**
     * get gift model for this shop.
     */
    public function gifts(): HasMany
    {
        return $this->hasMany(Gift::class, 'shop_id');
    }

    /**
     * Get the logo media for the Shop.
     */
    public function mediaLogo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    /**
     * Retrieve the media banner for this instance.
     */
    public function mediaBanner(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner_id');
    }

    /**
     * get all gallery images for this shop
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'shop_id');
    }

    /**
     * Get the logo for the Shop as an attribute.
     */
    public function logo(): Attribute
    {
        $logo = asset('defualt/defualt.jpg');
        if ($this->mediaLogo && Storage::exists($this->mediaLogo->src)) {
            $logo = Storage::url($this->mediaLogo->src);
        }

        return Attribute::make(
            get: fn () => $logo
        );
    }

    /**
     * Get the banner for the Shop as an attribute.
     */
    public function banner(): Attribute
    {
        $banner = asset('defualt/defualt.jpg');
        if ($this->mediaBanner && Storage::exists($this->mediaBanner->src)) {
            $banner = Storage::url($this->mediaBanner->src);
        }

        return Attribute::make(
            get: fn () => $banner
        );
    }

    /**
     * Get all of the products for the Shop.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Retrieve the categories associated with the shop.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'shop_categories');
    }

    /**
     * Retrieve the sub categories associated with the shop.
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * get all of the brands for the shop.
     */
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    /**
     * Get all of the coupons for the Shop.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get all of the colors for the Shop.
     */
    public function colors(): HasMany
    {
        return $this->hasMany(Color::class);
    }

    /**
     * Get the sizes for the shop.
     */
    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class, 'shop_id');
    }

    /**
     * Get all of the units for the Shop.
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Get all of the orders for the Shop.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the banners for the Shop.
     */
    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class, 'shop_id');
    }

    /**
     * Scope a query to only include active shops.
     *
     * @param  Builder  $builder  The query builder
     * @return mixed
     */
    public function scopeIsActive(Builder $builder)
    {
        return $builder->whereHas('user', function ($query) {
            $query->where('is_active', 1);
        });
    }

    /**
     * Get all of the reviews for the Shop.
     *
     * @return HasMany.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'shop_id');
    }

    /**
     * Calculates the average rating of the reviews.
     *
     * @return Attribute The average rating attribute.
     */
    public function averageRating(): Attribute
    {
        $avgRating = $this->reviews()->avg('rating');

        return new Attribute(
            get: fn () => (float) number_format($avgRating > 0 ? $avgRating : 5, 1, '.', ''),
        );
    }
}
