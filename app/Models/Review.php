<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'customer_id',
        'product_id',
        'shop_id',
        'order_id',
        'rating',
        'description',
    ];

    /**
     * Get the customer associated with this model.
     *
     * @return BelongsTo The customer associated with this model.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the product from this model.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

        /**
     * Boot method for the Review model.
     *
     * This method is called when the Review model is booted. It adds a global scope
     * to the model using the ActiveScope class.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope());
    }
}
