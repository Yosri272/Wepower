<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use App\Repositories\UnitRepository;

class UnitController extends Controller
{
    /**
     * Display the unit list.
     */
    public function index()
    {
        $units = auth()->user()->shop->units()->paginate(20);

        return view('shop.unit.index', compact('units'));
    }

    /**
     * store a new unit
     */
    public function store(UnitRequest $request)
    {
        UnitRepository::storeByRequest($request);

        return to_route('shop.unit.index')->withSuccess(__('Unit created successfully'));
    }

    /**
     * update a unit
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        UnitRepository::updateByRequest($request, $unit);

        return to_route('shop.unit.index')->withSuccess(__('Unit updated successfully'));
    }

    /**
     * status toggle a unit
     */
    public function statusToggle(Unit $unit)
    {
        $unit->update([
            'is_active' => ! $unit->is_active,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }
}
