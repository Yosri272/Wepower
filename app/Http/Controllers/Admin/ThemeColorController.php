<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneraleSetting;
use App\Repositories\GeneraleSettingRepository;
use Illuminate\Http\Request;

class ThemeColorController extends Controller
{
    public function index()
    {
        $themeColors = config('themeColors.shades');

        $generaleSetting = GeneraleSetting::first();

        $primaryColor = array_key_exists($generaleSetting?->primary_color, $themeColors) ? $themeColors[$generaleSetting?->primary_color] : null;

        $primary = $primaryColor ? $generaleSetting?->primary_color : '#EE456B';
        $secondary = $primaryColor ? $primaryColor['100'] : '#FEE5E8';

        return view('admin.theme-color', compact('themeColors', 'primary', 'secondary'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required',
            'secondary_color' => 'required',
        ]);

        GeneraleSettingRepository::updateOrCreateThemeColor($request);

        return back()->with('success', __('Theme color updated successfully'));
    }
}
