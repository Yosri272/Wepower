<?php

namespace App\Repositories;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Address::class;
    }

    /**
     * Store an address using the given request.
     *
     * @param  AddressRequest  $request  The address request
     */
    public static function storeByRequest(AddressRequest $request): Address
    {
        $isDefault = $request->is_default ? true : false;
        $customer = auth()->user()->customer;
        if ($isDefault && $customer->addresses) {
            $customer->addresses()->update(['is_default' => 0]);
        }

        return self::create([
            'customer_id' => auth()->user()->customer->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'area' => $request->area,
            'flat_no' => $request->flat_no,
            'post_code' => $request->post_code,
            'address_line' => $request->address_line,
            'address_line2' => $request->address_line2,
            'address_type' => $request->address_type,
            'is_default' => $customer->addresses ? $isDefault : true,
            'langitude' => $request->longitude,
            'longitude' => $request->longitude,
        ]);
    }

    /**
     * Update an address using the provided request data.
     *
     * @param  AddressRequest  $request  The request data for the address update
     * @return Address The updated address
     */
    public static function updateByRequest(AddressRequest $request, Address $address): Address
    {
        $isDefault = $request->is_default ? true : false;
        $customer = auth()->user()->customer;
        if ($isDefault && $customer->addresses) {
            $customer->addresses()->update(['is_default' => 0]);
        }
        $default = $customer->addresses()->where('is_default')->count();
        $address->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'area' => $request->area,
            'flat_no' => $request->flat_no,
            'post_code' => $request->post_code,
            'address_line' => $request->address_line,
            'address_line2' => $request->address_line2,
            'address_type' => $request->address_type,
            'is_default' => $customer->addresses ? ($default > 0 ? $isDefault : true) : true,
        ]);

        return $address;
    }
}
