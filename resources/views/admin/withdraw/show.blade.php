@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Withdraws') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="card mt-3">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                <h4 class="fz-20 m-0">
                    {{ __('Shop Withdraw Information') }}
                </h4>
                <i class="bi bi-wallet2 fz-18"></i>
            </div>

            <div class="card-body d-flex justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="mb-2 fw-bold"> {{__('Amount')}}: {{ showCurrency($withdraw->amount) }}</div>
                    <div>
                        <span class="fw-bold">{{__('Requested At')}}:</span>
                        <span>{{ $withdraw->created_at }}</span>
                    </div>
                </div>

                <div>
                    <div class="mb-2 fw-medium">{{__('')}}:</div>
                    <div>{{ $withdraw->reason }}</div>
                </div>

                <div>
                    @if ($withdraw->status == 'pending')
                        <button class="btn btn-primary py-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                            {{ __('Proceed Next') }}
                             <i class="bi bi-arrow-right"></i>
                        </button>
                    @else
                        @if ($withdraw->status == 'pending')
                            <span class="withdrawStatus badgePending">
                                <i class="bi bi-exclamation-triangle"></i>
                                {{ __($withdraw->status) }}
                            </span>
                        @elseif($withdraw->status == 'approved')
                            <span class="withdrawStatus badgeApproved">
                                <i class="bi bi-check2-all"></i>
                                {{ __($withdraw->status) }}
                            </span>
                        @else
                            <span class="withdrawStatus badgeDenied">
                                <i class="bi bi-x-octagon-fill"></i>
                                {{ __($withdraw->status) }}
                            </span>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mt-3">
                    <div class="card-header d-flex align-items-center justify-content-start gap-2 py-3">
                        <i class="bi bi-wallet2 fz-18"></i>
                        <h4 class="fz-20 m-0"> {{ __('Others Info') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="fw-medium">{{__('Contact Number')}}:</span>
                            <span>{{ $withdraw->contact_number }}</span>
                        </div>
                        <div>
                            <span class="fw-medium">{{__('Name')}}:</span>
                            <span>{{ $withdraw->name }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mt-3">
                    <div class="card-header d-flex align-items-center justify-content-start gap-2 py-3">
                        <i class="bi bi-person-circle fz-18"></i>
                        <h4 class="fz-20 m-0"> {{ __('User Info') }}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <span class="fw-medium">{{__('Name')}}:</span>
                            <span>{{ $withdraw->shop->user->name }}</span>
                        </div>

                        <div class="mt-2">
                            <span class="fw-medium">{{__('Email')}}:</span>
                            <span>{{ $withdraw->shop->user->email }}</span>
                        </div>

                        <div class="mt-2">
                            <span class="fw-medium">{{__('Phone')}}:</span>
                            <span>{{ $withdraw->shop->user->phone }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mt-3">
                    <div class="card-header d-flex align-items-center justify-content-start gap-2 py-3">
                        <i class="bi bi-shop fz-18"></i>
                        <h4 class="fz-20 m-0"> {{ __('Shop Info') }}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <span class="fw-medium">{{__('Name')}}:</span>
                            <span>{{ $withdraw->shop->name }}</span>
                        </div>

                        <div class="mt-2">
                            <span class="fw-medium">{{__('Logo')}}:</span>
                            <span><img src="{{ asset($withdraw->shop->logo) }}" class="img-thumbnail" alt=""
                                    width="40"></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Withdraw Modal -->
    <form action="{{ route('admin.withdraw.update', $withdraw->id) }}" method="POST">
        @csrf
        <div class="modal fade" id="withdrawModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">{{ __('Withdraw Update') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <x-select name="status" label="Withdraw Status" required="true">
                                <option value="approved">{{ __('Approved') }}</option>
                                <option value="denied">{{ __('Denied') }}</option>
                            </x-select>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">{{ __('Any Reason') }}</label>
                            <textarea name="reason" placeholder="{{ __('Enter Any Reason') }}" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
