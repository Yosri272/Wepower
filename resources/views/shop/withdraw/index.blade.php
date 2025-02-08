@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Withdraws') }}
        </h4>

        <button type="button" data-bs-toggle="modal" data-bs-target="#withdrawModal" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Add Withdraw')}}
        </button>

    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border table-responsive-md">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}</th>
                                <th>{{ __('Amount') }}</th>
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
                                    @if ($withdraw->status == 'pending')
                                        <a href="{{ route('shop.withdraw.delete', $withdraw->id) }}"
                                            class="btn btn-sm btn-primary confirm">
                                            {{__('Cancel Withdraw')}}
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-primary" disabled>
                                           {{__(' No Action')}}
                                        </button>
                                    @endif

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

    <!-- Withdraw Modal -->
    <form id="withdrawForm" method="POST">
        @csrf
        <div class="modal fade" id="withdrawModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            {{__('Withdraw Request')}}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label class="form-label">
                               {{__('Withdraw Amount')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                required>

                            <p class="text-danger" id="amount-error"></p>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                {{__('Name')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="{{__('Name')}}" required>
                            <span class="text-danger" id="name-error"></span>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                {{__('Contact Number')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control"
                                placeholder="Enter contact number"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                required>
                            <span class="text-danger" id="contact_number-error"></span>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                {{__('Any message')}}
                            </label>
                            <textarea name="message" placeholder="{{__('Any message')}}" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('Close')}}
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            {{__('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(".confirm").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "{{__('Are you sure?')}}",
                text: "{{__('If you cancel this request, it will be deleted permanently!')}}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{__('Yes, Cancel it!')}}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        $('#withdrawForm').on('submit', function(e) {
            e.preventDefault();
            const amount = $('#amount').val();
            const name = $('#name').val();
            const contact_number = $('#contact_number').val();
            const message = $('#message').val();
            $('#submitBtn').prop('disabled', true);
            $.ajax({
                url: "{{ route('shop.withdraw.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    amount: amount,
                    name: name,
                    contact_number: contact_number,
                    message: message,
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },
                error: function(response) {
                    $('#submitBtn').prop('disabled', false);
                    const errors = response.responseJSON.errors;
                    if (errors && errors.amount) {
                        $('#amount').addClass('is-invalid');
                        $('#amount-error').text(errors.amount[0]);
                    }
                    if (errors && errors.name) {
                        $('#name').addClass('is-invalid');
                        $('#name-error').text(errors.name[0]);
                    }
                    if (errors && errors.contact_number) {
                        $('#contact_number').addClass('is-invalid');
                        $('#contact_number-error').text(errors.contact_number[0]);
                    }

                    if (!errors) {
                        Swal.fire({
                            title: response.responseJSON.message,
                            icon: "warning",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Ok"
                        });
                    }
                }

            });
        });
    </script>
@endpush
