<?php

namespace App\Repositories;

use App\Http\Requests\SizeRequest;
use App\Models\Size;

class SizeRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Size::class;
    }

    /**
     * store new size.
     *
     * @param  \App\Http\Requests\SizeRequest  $request
     *                                                   return \App\Models\Size
     * */
    public static function storeByRequest(SizeRequest $request): Size
    {
        return self::create([
            'name' => $request->name,
            'shop_id' => auth()->user()->shop->id,
            'is_active' => true,
        ]);
    }

    /**
     * Update the size.
     *
     * @param  \App\Http\Requests\SizeRequest  $request
     *                                                   return \App\Models\Size
     * */
    public static function updateByRequest(SizeRequest $request, Size $size): Size
    {
        $size->update([
            'name' => $request->name,
        ]);

        return $size;
    }
}
