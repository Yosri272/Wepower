@extends('layouts.app')

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-image"></i> {{__('Add New Delivery Charge')}}
        </div>
    </div>
    <form action="{{ route('admin.deliveryCharge.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-input label="Minimum Order Quantity" name="min_order_qty" type="text"
                                    placeholder="Enter Minimum Order Quantity" onlyNumber />
                            </div>
                            <div class="col-md-6">
                                <x-input label="Maximum Order Quantity" name="max_order_qty" type="text"
                                    placeholder="Enter Maximum Order Quantity" onlyNumber />
                            </div>
                            <div class="col-md-6 mt-3">
                                <x-input label="Delivery Charge" name="delivery_charge" type="text" placeholder="Enter Delivery Charge" onlyNumber />
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button class="btn btn-primary py-2 px-5">
                                {{__('Submit')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
