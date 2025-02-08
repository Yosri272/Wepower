@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4>{{ __('Withdraws') }}</h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                <h5 class="card-title m-0">
                    {{ __('Withdraw Requests') }}
                </h5>

                <div class="dropdown" style="width: 160px">
                    <a class="btn border  text-start dropdown-toggle w-100" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __(request()->status ? ucfirst(request()->status) : 'All') }}
                    </a>
                    <ul class="dropdown-menu w-100">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.withdraw.index') }}">
                                {{ __('All') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.withdraw.index', 'status=pending') }}">
                                {{ __('Pending') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.withdraw.index', 'status=approved') }}">
                                {{ __('Approved') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.withdraw.index', 'status=denied') }}">
                                {{ __('Denied') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Request Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($withdraws as $key => $withdraw)
                            @php
                                $serial = $withdraws->firstItem() + $key;
                            @endphp
                            <tr>
                                <td>{{ $serial }}</td>
                                <td>{{ $withdraw->amount }}</td>
                                <td>{{ $withdraw->name }}</td>

                                <td>
                                    {{ $withdraw->created_at->format('F d, Y') }} <br>
                                    <small>{{ $withdraw->created_at->diffForHumans() }}</small>
                                </td>

                                <td>
                                    @if ($withdraw->status == 'pending')
                                        <span class="badge badgePending">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            {{ __($withdraw->status) }}
                                        </span>
                                    @elseif($withdraw->status == 'approved')
                                        <span class="badge badgeApproved">
                                            <i class="bi bi-check2-all"></i>
                                            {{ __($withdraw->status) }}
                                        </span>
                                    @else
                                        <span class="badge badgeDenied">
                                            <i class="bi bi-x-octagon-fill"></i>
                                            {{ __($withdraw->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('admin.withdraw.show', $withdraw->id) }}"
                                        class="btn btn-sm btn-outline-primary circleIcon">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $withdraws->withQueryString()->links() }}
        </div>

    </div>
@endsection
