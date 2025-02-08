<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get media
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Get logo
     */
    public function logo(): Attribute
    {
        $logo = asset('assets/gateway').'/'.$this->alias.'.png';

        if ($this->media && Storage::exists($this->media->src)) {
            $logo = Storage::url($this->media->src);
        }

        return Attribute::make(
            get: fn () => $logo
        );
    }
}
