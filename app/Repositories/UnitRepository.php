<?php

namespace App\Repositories;

use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Unit::class;
    }

    /**
     * Store unit by request.
     *
     * @param  UnitRequest  $request  The unit request
     */
    public static function storeByRequest(UnitRequest $request): Unit
    {
        return self::create([
            'name' => $request->name,
            'shop_id' => auth()->user()->shop->id,
            'is_active' => true,
        ]);
    }

    /**
     * Update unit by request.
     *
     * @param  UnitRequest  $request  The unit request
     * @param  Unit  $unit  The unit to update
     * @return Unit The updated unit
     */
    public static function updateByRequest(UnitRequest $request, Unit $unit): Unit
    {
        $unit->update([
            'name' => $request->name,
        ]);

        return $unit;
    }
}
