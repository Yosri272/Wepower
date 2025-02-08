<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function srcUrl(): Attribute
    {
        $image = asset('defualt/defualt.jpg');

        if (Storage::exists($this->src)) {
            $image = Storage::url($this->src);
        }

        return Attribute::make(
            get: fn () => $image,
        );
    }
}
