<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Retrieves the products associated with this instance.
     *
     * @return BelongsToMany The products associated with this instance.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, 'category_subcategories')->where('is_active', 1);
    }

    /**
     * Scopes a query to only include active records.
     *
     * @param  mixed  $query  The query parameter.
     * @return mixed The return value.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Retrieves the associated media for this model.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Generates a thumbnail attribute for the media.
     *
     * @return Attribute The generated thumbnail attribute.
     */
    public function thumbnail(): Attribute
    {
        $thumbnail = asset('defualt/defualt.jpg');
        if ($this->media && Storage::exists($this->media->src)) {
            $thumbnail = Storage::url($this->media->src);
        }

        return Attribute::make(
            get: fn () => $thumbnail
        );
    }
}
