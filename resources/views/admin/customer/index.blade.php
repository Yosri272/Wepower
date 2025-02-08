@extends('layouts.app')
@section('content')
    <div>
        <h4>{{ __('All Customers') }}</h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}.</th>
                                <th>{{ __('Profile') }}</th>
                                <th style="min-width: 150px">{{ __('Name') }}</th>
                                <th style="min-width: 100px">{{ __('Phone') }}</th>
                                <th class="text-center">{{ __('Email') }}</th>
                                <th class="text-center">{{ __('Gender') }}</th>
                                <th class="text-center">{{ __('Date of Birth') }}</th>

                                {{-- <th class="text-center">{{ __('Action') }}</th> --}}
                            </tr>
                        </thead>
                        @forelse($customers as $key => $customer)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <img src="{{ $customer->thumbnail }}" width="50">
                                </td>

                                <td>{{ Str::limit($customer->fullName, 50, '...') }}</td>

                                <td>
                                    {{ $customer->phone ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $customer->email ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    {{ $customer->gender ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                {{ $customer->date_of_birth ?? 'N/A' }}
                                </td>

                                {{-- <td class="text-center">
                                    <div class="d-flex gap-3 justify-content-center">
                                        <a href="{{ route('admin.product.show', $product->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td> --}}
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
            {{ $customers->withQueryString()->links() }}
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(".confirmApprove").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to approve this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Approve it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        const confirmDeny = (id) => {
            const form = document.getElementById('deleteForm');
            form.action = `{{ route('admin.product.destroy', ':id') }}`.replace(':id', id);
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this product! If you confirm, it will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Yes, Delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
