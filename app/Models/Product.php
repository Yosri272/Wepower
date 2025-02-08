<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Retrieve the shop that this model belongs to.
     *
     * @return BelongsTo The shop that this model belongs to.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Retrieve the categories associated with the current model.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * Retrieve the categories associated with the current model.
     */
    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, 'product_subcategories');
    }

    /**
     * Get the media record associated with the model.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Create a thumbnail for the media, with a default image if none is present.
     */
    public function thumbnail(): Attribute
    {
        $thumbnail = asset('defualt/defualt.jpg');
        if ($this->media && Storage::exists($this->media->src)) {
            $thumbnail = Storage::url($this->media->src);
        }

        return new Attribute(
            get: fn () => $thumbnail
        );
    }

    /**
     * Get the medias for the product.
     */
    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'product_thumbnails');
    }

    /**
     * Generate thumbnails for the medias.
     */
    public function thumbnails(): Collection
    {
        $thumbnails = collect([]);

        if (request()->is('api/*')) {
            $thumbnails[] = (object) [
                'id' => $this->media?->id,
                'thumbnail' => $this->thumbnail,
            ];
        }

        foreach ($this->medias as $media) {
            $thumbnail = asset('defualt/defualt.jpg');
            if ($media && Storage::exists($media->src)) {
                $thumbnail = Storage::url($media->src);
            }
            $thumbnails[] = (object) [
                'id' => $media?->id,
                'thumbnail' => $thumbnail,
            ];
        }

        return $thumbnails;
    }

    /**
     * Retrieves the reviews associated with this object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany The reviews associated with this object.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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
            get: fn () => (float) number_format($avgRating > 0 ? $avgRating : 5, 1, '.', '')
        );
    }

    /**
     * Retrieves the colors associated with this model.
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    /**
     * sizes function.
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_sizes')->withPivot('price');
    }

    /**
     * get the brand that owns the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * get the units that owns the product.
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'product_units');
    }

    /**
     * Retrieve the orders associated with the model.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_products')->withPivot('quantity', 'color', 'unit', 'size');
    }

    /**
     * Filter the given builder by active status.
     *
     * @param  Builder  $builder  The builder to filter.
     * @return Builder The filtered builder.
     */
    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('is_active', true)->where('is_approve', true)->whereHas('shop', function ($query) {
            return $query->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            });
        });
    }

    /**
     * Calculate the discount percentage based on the given price and discount price.
     */
    public static function getDiscountPercentage($price, $discountPrice)
    {
        return $discountPrice ? ($price - $discountPrice) * 100 / $price : 0;
    }

    /**
     * get the favorites from the model.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
