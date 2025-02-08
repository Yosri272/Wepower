<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Repositories\AddressRepository;

class AddressController extends Controller
{
    /**
     * Display a listing of the addresses.
     *
     * @return json Response
     */
    public function index()
    {
        $request = request();

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        // get all addresses which logged in user and order by descending
        $addresses = auth()->user()->customer->addresses()
            ->when($perPage && $page, function ($query) use ($perPage, $skip) {
                return $query->skip($skip)->take($perPage);
            })->orderByDesc('is_default')->orderByDesc('id')->get();

        return $this->json('all addresses', [
            'total' => auth()->user()->customer->addresses->count(),
            'addresses' => AddressResource::collection($addresses),
        ]);
    }

    /**
     * Store the address from the given request.
     *
     * @param  AddressRequest  $request  The request data for the address
     * @return mixed
     */
    public function store(AddressRequest $request)
    {
        // Create a new address for the logged-in user which he requests
        $address = AddressRepository::storeByRequest($request);

        return $this->json('address created successfully', [
            'address' => AddressResource::make($address),
        ]);
    }

    /**
     * Update the address using the given request and address.
     *
     * @param  AddressRequest  $request  The request data for updating the address
     * @param  Address  $address  The address to be updated
     */
    public function update(AddressRequest $request, Address $address)
    {
        // update address which he want to update
        $address = AddressRepository::updateByRequest($request, $address);

        return $this->json('address updated successfully', [
            'address' => AddressResource::make($address),
        ]);
    }

    /**
     * Destroy an address if it has no orders, otherwise return an error.
     *
     * @param  Address  $address  The address to be destroyed
     * @return JSON The result of the operation
     */
    public function destroy(Address $address)
    {
        //When the user tries to delete an address check it exists
        if ($address->orders->isEmpty()) {
            $address->delete();

            return $this->json('address deleted successfully');
        }

        return $this->json('address can not be deleted because it has orders', [], 422);
    }
}
