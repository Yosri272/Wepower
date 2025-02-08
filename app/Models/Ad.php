<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

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
