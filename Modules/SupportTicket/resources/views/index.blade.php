@extends('supportticket::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
        <h4>
            {{__('All Support Tickets')}}
        </h4>

        <div>
            <span class="text-muted">
                {{__('Short By')}}:</span>
            <div class="dropdown">
                <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false" style="min-width: 80px">
                   {{ __(request()->status ?? 'All') }}
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.supportTicket.index') }}">{{ __('All') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.supportTicket.index','status=pending') }}">{{ __('Pending') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.supportTicket.index','status=confirm') }}">{{ __('Confirm') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.supportTicket.index','status=completed') }}">{{ __('Completed') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body d-flex flex-column gap-3">
            @forelse ($supportTickets as $supportTicket)
                <div class="ticket card">
                    <div class="card-body">
                        <!---- header -->
                        <div class="d-flex justify-content-between gap-2 flex-wrap border-bottom pb-2">
                            <span class="text-muted">
                                {{ $supportTicket->created_at->format('d F, Y') }}
                            </span>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="ticket-number">#{{ $supportTicket->ticket_number }}</span>
                                <span class="ticket-status {{ $supportTicket->status }}">
                                    {{ $supportTicket->status }}
                                </span>
                            </div>
                        </div>

                        <!---- content -->

                        <a href="{{ route('admin.supportTicket.show', $supportTicket->id) }}"
                            class="d-flex justify-content-between align-items-center gap-2 flex-wrap pt-2">
                            <div class="ticket-item">
                                <div class="text-muted">{{ __('Order Number') }}</div>
                                <div>{{ $supportTicket->order_number ?? __('N/A') }}</div>
                            </div>

                            <div class="ticket-item">
                                <div class="text-muted">{{ __('Issue Type') }}</div>
                                <div class="ticket-type">{{ __($supportTicket->issue_type) }}</div>
                            </div>

                            <div class="ticket-item">
                                <div class="text-muted">{{ __('Subject') }}</div>
                                <div class="ticket-subject">{{ __($supportTicket->subject) }}</div>
                            </div>

                            <div class="icon">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <p>
                    {{__('No Support Tickets Found!')}}
                </p>
            @endforelse
        </div>
    </div>

    <div class="py-3">
        {{ $supportTickets->withQueryString()->links() }}
    </div>
@endsection
