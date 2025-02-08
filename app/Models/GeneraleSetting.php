<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GeneraleSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'generate_settings';

    public function mediaLogo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function mediaAppLogo()
    {
        return $this->belongsTo(Media::class, 'app_logo_id');
    }

    public function mediaFavicon()
    {
        return $this->belongsTo(Media::class, 'favicon_id');
    }

    public function mediaFooterLogo()
    {
        return $this->belongsTo(Media::class, 'footer_logo_id');
    }

    public function mediaFooterQr()
    {
        return $this->belongsTo(Media::class, 'footer_qrcode_id');
    }

    public function logo(): Attribute
    {
        $logo = asset('assets/logo.png');
        if ($this->mediaLogo && Storage::exists($this->mediaLogo->src)) {
            $logo = Storage::url($this->mediaLogo->src);
        }

        return new Attribute(
            get: fn () => $logo,
        );
    }

    public function appLogo(): Attribute
    {
        $logo = asset('assets/favicon.png');
        if ($this->mediaAppLogo && Storage::exists($this->mediaAppLogo->src)) {
            $logo = Storage::url($this->mediaAppLogo->src);
        }

        return new Attribute(
            get: fn () => $logo,
        );
    }

    public function favicon(): Attribute
    {
        $favicon = asset('assets/favicon.png');
        if ($this->mediaFavicon && Storage::exists($this->mediaFavicon->src)) {
            $favicon = Storage::url($this->mediaFavicon->src);
        }

        return new Attribute(
            get: fn () => $favicon,
        );
    }

    public function footerLogo(): Attribute
    {
        $logo = asset('assets/logoWhite.png');
        if ($this->mediaFooterLogo && Storage::exists($this->mediaFooterLogo->src)) {
            $logo = Storage::url($this->mediaFooterLogo->src);
        }

        return new Attribute(
            get: fn () => $logo,
        );
    }

    public function footerQr(): Attribute
    {
        $qr = null;
        if ($this->mediaFooterQr && Storage::exists($this->mediaFooterQr->src)) {
            $qr = Storage::url($this->mediaFooterQr->src);
        }

        return new Attribute(
            get: fn () => $qr,
        );
    }
}
