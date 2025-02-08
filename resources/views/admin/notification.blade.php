@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Notifications') }}
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
                                        <th>{{ __('Icon') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $notification)
                                        <tr>
                                            <td>
                                                <div class="iconBox {{ $notification->type == 'danger' ? 'cardIcon' : 'pdfIcon' }}">
                                                    <i class="bi {{ $notification->icon }}"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.notification.read', $notification->id) }}" class="text-dark {{ $notification->is_read ? 'text-decoration-line-through' : 'fw-bold' }}">{{ $notification->title }}</a>
                                            </td>
                                            <td>{{ $notification->content }}</td>
                                            <td>
                                                <a href="{{ route('admin.notification.destroy', $notification->id) }}"
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

                {{ $notifications->links() }}

            </div>
        </div>
    </div>
@endsection
