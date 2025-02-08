@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Support Messages') }}
        </h4>
    </div>

    <div class="mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card rounded-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supports as $support)
                                        <tr>
                                            <td>{{ $support->subject }}</td>
                                            <td>{{ $support->message }}</td>
                                            <td>
                                                <a href="{{ route('admin.support.delete', $support->id) }}"
                                                    class="btn btn-outline-danger circleIcon deleteConfirm"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    data-bs-title="{{__('Delete')}}">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{ $supports->links() }}

            </div>
        </div>
    </div>
@endsection
