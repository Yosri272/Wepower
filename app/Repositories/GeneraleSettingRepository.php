<?php

namespace App\Repositories;

use Abedin\Boiler\Repositories\Repository;
use App\Http\Requests\GeneraleSettingRequest;
use App\Models\GeneraleSetting;
use App\Models\Media;

class GeneraleSettingRepository extends Repository
{
    /**
     * Get the model used by the repository.
     *
     * @return string
     */
    public static function model()
    {
        return GeneraleSetting::class;
    }

    /**
     * Update the generale setting using the provided request.
     */
    public static function updateByRequest(GeneraleSettingRequest $request): void
    {
        $generaleSetting = GeneraleSetting::first();

        $faveIcon = self::faviconUpdate($request, $generaleSetting?->mediaFavicon);
        $logo = self::logoUpdate($request, $generaleSetting?->mediaLogo);
        $appLogo = self::appLogoUpdate($request, $generaleSetting?->mediaAppLogo);
        $footerLogo = self::footerLogoUpdate($request, $generaleSetting?->mediaFooterLogo);
        $footerQr = self::footerQrUpdate($request, $generaleSetting?->mediaFooterQr);

        self::query()->updateOrCreate(
            [
                'id' => $generaleSetting?->id ?? null,
            ],
            [
                'name' => $request->name,
                'title' => $request->title,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'show_download_app' => $request->show_download_app ? true : false,
                'google_playstore_url' => $request->google_playstore_url,
                'app_store_url' => $request->app_store_url,
                'currency' => $request->currency,
                'currency_position' => $request->currency_position,
                'direction' => $request->direction,
                'favicon_id' => $faveIcon?->id ?? $generaleSetting?->favicon_id,
                'logo_id' => $logo?->id ?? $generaleSetting?->logo_id,
                'show_footer' => $request->show_footer ? true : false,
                'footer_phone' => $request->footer_phone,
                'footer_email' => $request->footer_email,
                'footer_text' => $request->footer_text,
                'footer_description' => $request->footer_description,
                'footer_logo_id' => $footerLogo?->id ?? $generaleSetting?->footer_logo_id,
                'footer_qrcode_id' => $footerQr?->id ?? $generaleSetting?->footer_qrcode_id,
                'app_logo_id' => $appLogo?->id ?? $generaleSetting?->app_logo_id,
            ]
        );
    }

    /**
     * Update the logo using the provided request and logo.
     *
     * @param  Media  $logo
     */
    public static function logoUpdate($request, $media): ?Media
    {
        if ($request->hasFile('logo') && $media != null) {
            return MediaRepository::updateByRequest($request->logo, 'logo', 'image', $media);
        }

        if ($request->hasFile('logo') && $media == null) {
            return MediaRepository::storeByRequest($request->logo, 'logo', 'image');
        }

        return null;
    }

    /**
     * Update the app logo using the provided request.
     *
     * @param  Media  $logo
     */
    public static function appLogoUpdate($request, $media): ?Media
    {
        if ($request->hasFile('app_logo') && $media != null) {
            return MediaRepository::updateByRequest($request->app_logo, 'logo', 'image', $media);
        }

        if ($request->hasFile('app_logo') && $media == null) {
            return MediaRepository::storeByRequest($request->app_logo, 'logo', 'image');
        }

        return null;
    }

    /**
     * Update the favicon using the provided request and favicon.
     *
     * @param  Media  $favicon
     */
    public static function faviconUpdate($request, $media): ?Media
    {
        if ($request->hasFile('favicon') && $media != null) {
            return MediaRepository::updateByRequest($request->favicon, 'favicon', 'image', $media);
        }

        if ($request->hasFile('favicon') && $media == null) {
            return MediaRepository::storeByRequest($request->favicon, 'favicon', 'image');
        }

        return null;
    }

    /**
     * Update or create the fronted footer logo.
     *
     * @param  Media  $favicon
     */
    public static function footerLogoUpdate($request, $media): ?Media
    {
        if ($request->hasFile('footer_logo') && $media != null) {
            return MediaRepository::updateByRequest($request->footer_logo, 'logo', 'image', $media);
        }

        if ($request->hasFile('footer_logo') && $media == null) {
            return MediaRepository::storeByRequest($request->footer_logo, 'logo', 'image');
        }

        return null;
    }

    /**
     * Update or create the fronted footer qr scanner.
     *
     * @param  Media  $favicon
     */
    public static function footerQrUpdate($request, $media): ?Media
    {
        if ($request->hasFile('footer_qrcode') && $media != null) {
            return MediaRepository::updateByRequest($request->footer_qrcode, 'qrscanner', 'image', $media);
        }

        if ($request->hasFile('footer_qrcode') && $media == null) {
            return MediaRepository::storeByRequest($request->footer_qrcode, 'qrscanner', 'image');
        }

        return null;
    }

    /**
     * Update the generale setting using the provided request.
     */
    public static function updateOrCreateByRequest($request): GeneraleSetting
    {
        $generaleSetting = self::query()->first();

        return self::query()->updateOrCreate([
            'id' => $generaleSetting?->id ?? null,
        ], [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'currency_position' => $request->currency_position,
            'shop_type' => $request->shop_type ?? 'multi',
        ]);
    }

    /**
     * update shop info
     */
    public static function updateOrCreateShopSetting($request): GeneraleSetting
    {
        $generaleSetting = self::query()->first();

        return self::query()->updateOrCreate([
            'id' => $generaleSetting?->id ?? null,
        ], [
            'commission' => $request->commission,
            'commission_type' => $request->commission_type,
            'commission_charge' => $request->commission_charge,
            'new_product_approval' => $request->new_product_approval ? true : false,
            'update_product_approval' => $request->update_product_approval ? true : false,
        ]);
    }

    /**
     * update withdeaw info
     */
    public static function updateOrCreateWithdrawSetting($request): GeneraleSetting
    {
        $generaleSetting = self::query()->first();

        return self::query()->updateOrCreate([
            'id' => $generaleSetting?->id ?? null,
        ], [
            'min_withdraw' => $request->min_withdraw,
            'max_withdraw' => $request->max_withdraw,
            'withdraw_request' => $request->withdraw_request,
        ]);
    }

    /**
     * setup theme colors
     */
    public static function updateOrCreateThemeColor($request): GeneraleSetting
    {
        $generaleSetting = self::query()->first();

        return self::query()->updateOrCreate([
            'id' => $generaleSetting?->id ?? null,
        ], [
            'primary_color' => $request->primary_color ?? '#8b5cf6',
            'secondary_color' => $request->secondary_color ?? '#ede9fe',
        ]);
    }
}
