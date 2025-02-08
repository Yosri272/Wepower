<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeRequest;
use App\Models\Size;
use App\Repositories\SizeRepository;

class SizeController extends Controller
{
    /**
     * Display the size list.
     */
    public function index()
    {
        $sizes = auth()->user()->shop->sizes()->paginate(20);

        return view('shop.size.index', compact('sizes'));
    }

    /**
     * store a new size
     */
    public function store(SizeRequest $request)
    {
        SizeRepository::storeByRequest($request);

        return to_route('shop.size.index')->withSuccess(__('Size created successfully'));
    }

    /**
     * update a size
     */
    public function update(SizeRequest $request, Size $size)
    {
        SizeRepository::updateByRequest($request, $size);

        return to_route('shop.size.index')->withSuccess(__('Size updated successfully'));
    }

    /**
     * status toggle a size
     */
    public function statusToggle(Size $size)
    {
        $size->update([
            'is_active' => ! $size->is_active,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }
}
