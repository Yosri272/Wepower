<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentGatewayResource;
use App\Http\Resources\SocialLinkResource;
use App\Models\GeneraleSetting;
use App\Models\PaymentGateway;
use App\Models\SocialLink;

class MasterController extends Controller
{
    /**
     * Get master data for all.
     *
     * @return mixed
     */
    public function index()
    {
        $generaleSetting = GeneraleSetting::first();

        $paymentGateways = PaymentGateway::where('is_active', true)->get();
        $shopType = $generaleSetting?->shop_type ?? 'multi';

        $socialLinks = SocialLink::whereNotNull('link')->get();

        $themeColors = config('themeColors.shades');

        $currentColors = array_key_exists($generaleSetting?->primary_color, $themeColors) ? $themeColors[$generaleSetting?->primary_color] : null;

        $themeColors = (object) [
            'primary' => $currentColors ? $currentColors['500'] : '#EE456B',
            'primary50' => $currentColors ? $currentColors['50'] : '#FFF1F3',
            'primary100' => $currentColors ? $currentColors['100'] : '#FEE5E8',
            'primary200' => $currentColors ? $currentColors['200'] : '#FCCFD6',
            'primary300' => $currentColors ? $currentColors['300'] : '#FAA7B5',
            'primary400' => $currentColors ? $currentColors['400'] : '#F7758F',
            'primary500' => $currentColors ? $currentColors['500'] : '#EE456B',
            'primary600' => $currentColors ? $currentColors['600'] : '#DD2C5C',
            'primary700' => $currentColors ? $currentColors['700'] : '#B91747',
            'primary800' => $currentColors ? $currentColors['800'] : '#9B1642',
            'primary900' => $currentColors ? $currentColors['900'] : '#84173E',
            'primary950' => $currentColors ? $currentColors['950'] : '#4A071D',
        ];

        return $this->json('Master data', [
            'currency' => [
                'symbol' => $generaleSetting?->currency ?? '$',
                'position' => $generaleSetting?->currency_position ?? 'prefix',
            ],
            'show_download_app' => (bool) ($generaleSetting?->show_download_app ?? true),
            'google_playstore_link' => $generaleSetting?->google_playstore_url ?? null,
            'app_store_link' => $generaleSetting?->app_store_url ?? null,
            'payment_gateways' => PaymentGatewayResource::collection($paymentGateways),
            'multi_vendor' => (bool) ($shopType == 'multi' ? true : false),
            'mobile' => $generaleSetting?->footer_phone ?? '+88 01365 236 543',
            'email' => $generaleSetting?->footer_email ?? 'support@readyeCommerce.com',
            'address' => $generaleSetting?->address ?? 'Dhaka, Bangladesh',
            'web_show_footer' => (bool) ($generaleSetting?->show_footer ?? true),
            'web_footer_text' => $generaleSetting?->footer_text ?? 'All right reserved by RazinSoft',
            'web_footer_description' => $generaleSetting?->footer_description ?? 'The ultimate all-in-one solution for your eCommerce business worldwide.',
            'web_logo' => $generaleSetting?->logo ?? asset('assets/logo.png'),
            'web_footer_logo' => $generaleSetting?->footerLogo ?? asset('assets/logoWhite.png'),
            'app_name' => $generaleSetting?->name ?? config('app.name'),
            'app_logo' => $generaleSetting?->appLogo ?? asset('assets/favicon.png'),
            'footer_qr' => $generaleSetting?->footerQr ?? null,
            'social_links' => SocialLinkResource::collection($socialLinks),
            'theme_colors' => $themeColors,
            'pusher_app_key' => config('broadcasting.connections.pusher.key'),
            'pusher_app_cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'app_environment' => config('app.env'),
        ]);
    }
}
