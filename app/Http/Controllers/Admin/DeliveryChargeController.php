<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryChargeRequest;
use App\Models\DeliveryCharge;
use App\Repositories\DeliveryChargeRepository;

class DeliveryChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryCharges = DeliveryCharge::paginate(10);

        return view('admin.delivery-charge.index', compact('deliveryCharges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.delivery-charge.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryChargeRequest $request)
    {
        DeliveryChargeRepository::storeByRequest($request);

        return to_route('admin.deliveryCharge.index')->withSuccess(__('Delivery charge created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryCharge $deliveryCharge)
    {
        return view('admin.delivery-charge.edit', compact('deliveryCharge'));
    }

    /**
     * Update the specified resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(DeliveryChargeRequest $request, DeliveryCharge $deliveryCharge)
    {
        DeliveryChargeRepository::updateByRequest($request, $deliveryCharge);

        return to_route('admin.deliveryCharge.index')->withSuccess(__('Delivery charge updated successfully'));
    }

    /**
     * Remove the specified item from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryCharge $deliveryCharge)
    {
        $deliveryCharge->delete();

        return to_route('admin.deliveryCharge.index')->withSuccess(__('Delivery charge deleted successfully'));
    }
}
