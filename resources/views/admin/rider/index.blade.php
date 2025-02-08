@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4>
            {{ __('All Riders') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                <h5 class="card-title m-0"> {{ __('Riders') }}</h5>

                <div class="d-flex gap-3">
                    <a href="{{ route('admin.rider.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> {{ __('Add New') }}
                    </a>
                    <div class="dropdown" style="width: 160px">
                        <a class="btn border  text-start dropdown-toggle w-100" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __(request()->status ? ucfirst(request()->status) : 'All') }}
                        </a>
                        <ul class="dropdown-menu w-100">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.rider.index') }}">
                                    {{ __('All') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.rider.index', 'status=pending') }}">
                                    {{ __('Pending') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.rider.index', 'status=approved') }}">
                                    {{ __('Approved') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead class="table-light">
                            <tr>
                                <th>SL.</th>
                                <th>{{ __('Profile') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($riders as $key => $user)
                            @php
                                $serial = $riders->firstItem() + $key;
                            @endphp
                            <tr>
                                <td>{{ $serial }}</td>
                                <td>
                                    <img class="rounded-circle" src="{{ $user->thumbnail }}" width="40" height="40"
                                        loading="lazy" />
                                </td>
                                <td>{{ $user->fullName }}</td>

                                <td>
                                    {{ $user->phone }}
                                </td>

                                <td>
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="Change Active Status">
                                        <a href="{{ route('admin.rider.toggle', $user->id) }}" class="confirm">
                                            <input type="checkbox" {{ $user->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.rider.show', $user->id) }}"
                                        class="btn btn-sm btn-outline-primary circleIcon">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.rider.edit', $user->id) }}"
                                        class="btn btn-sm btn-outline-secondary circleIcon">
                                        <i class="bi bi-pencil-square"></i>
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
            {{ $riders->withQueryString()->links() }}
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        $(".confirm").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to change active status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
