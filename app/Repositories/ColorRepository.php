<?php

namespace App\Repositories;

use App\Http\Requests\ColorRequest;
use App\Models\Color;

class ColorRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Color::class;
    }

    /**
     * store a new color
     */
    public static function storeByRequest(ColorRequest $request): Color
    {
        return self::create([
            'name' => $request->name,
            'color_code' => $request->color_code,
            'shop_id' => auth()->user()->shop->id,
            'is_active' => true,
        ]);
    }

    /**
     * update a color
     */
    public static function updateByRequest(ColorRequest $request, Color $color): Color
    {
        $color->update([
            'name' => $request->name,
            'color_code' => $request->color_code,
        ]);

        return $color;
    }
}
