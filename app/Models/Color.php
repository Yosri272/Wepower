<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Scope a query to only include active colors.
     */
    public function scopeIsActive($query)
    {
        return $query->where('is_active', 1);
    }
}
