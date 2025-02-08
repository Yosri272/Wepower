@extends('supportticket::layouts.master')

@section('content')
    <h4>{{ __('Support Ticket') }} #{{ $supportTicket->ticket_number }}</h4>

    <div class="card mt-3 h-100">
        <div class="card-body">

            <div class="row">

                <div class="col-lg-6">
                    <div class="ticket-details">
                        <div class="ticket card">
                            <div class="card-body pt-2">
                                <div
                                    class="d-flex justify-content-between gap-2 flex-wrap border-bottom pb-2 align-items-center">
                                    <div>
                                        <span class="text-muted">
                                            {{ $supportTicket->created_at->format('d F, Y') }}
                                        </span>
                                        <span class="ticket-number">
                                            #{{ $supportTicket->ticket_number }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        @php
                                            $status = $supportTicket->status;

                                            $btnColor = 'primary';
                                            if ($status == 'pending') {
                                                $btnColor = 'warning';
                                            } elseif ($status == 'confirm') {
                                                $btnColor = 'primary';
                                            } elseif ($status == 'completed') {
                                                $btnColor = 'success';
                                            } elseif ($status == 'cancel') {
                                                $btnColor = 'danger';
                                            }
                                        @endphp

                                        <div class="dropdown">
                                            <a class="btn btn-{{ $btnColor }} dropdown-toggle text-capitalize text-white"
                                                href="#" role="button" data-bs-toggle="dropdown"
                                                aria-expanded="false" style="min-width: 80px">
                                                {{ $supportTicket->status }}
                                            </a>

                                            <ul class="dropdown-menu">
                                                @if ($supportTicket->status == 'pending')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.supportTicket.updateStatus', ['supportTicket' => $supportTicket->id, 'status' => 'confirm']) }}">
                                                            {{__('Confirm')}}
                                                        </a>
                                                    </li>
                                                @endif
                                                @if ($supportTicket->status == 'confirm')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.supportTicket.updateStatus', ['supportTicket' => $supportTicket->id, 'status' => 'completed']) }}">
                                                            {{__('Completed')}}
                                                        </a>
                                                    </li>
                                                @endif

                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.supportTicket.updateStatus', ['supportTicket' => $supportTicket->id, 'status' => 'cancel']) }}">
                                                        {{__('Cancel')}}
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>

                                        <button class="btn btn-primary" @if ($supportTicket->status == 'completed' || $supportTicket->status == 'cancel') disabled @endif
                                            data-bs-toggle="modal" data-bs-target="#setScheduleModal">
                                        {{ $supportTicket->ticket_start ? 'Update' : 'Set' }} {{__('Schedule')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap pt-2">
                                    <div class="ticket-item">
                                        <div class="text-muted">{{ __('Order Number') }}</div>
                                        <div>{{ $supportTicket->order_number ?? __('N/A') }}</div>
                                    </div>

                                    <div class="ticket-item">
                                        <div class="text-muted">{{ __('Issue Type') }}</div>
                                        <div class="ticket-type">{{ $supportTicket->issue_type }}</div>
                                    </div>
                                </div>

                                <div class="ticket-item mt-3">
                                    <div class="text-muted">{{ __('Subject') }}</div>
                                    <div class="ticket-subject">{{ $supportTicket->subject }}</div>
                                </div>

                                <h5 class="mt-4">{{ __('Contact Info') }}</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-muted">{{ __('Email') }}</div>
                                        <div class="ticket-subject">{{ $supportTicket->email }}</div>
                                    </div>
                                    <div class="col-md-6 mt-3 mt-md-0">
                                        <div class="text-muted">{{ __('Phone') }}</div>
                                        <div class="ticket-subject">{{ $supportTicket->phone ?? __('N/A') }}</div>
                                    </div>
                                </div>

                                <h5 class="mt-4">{{ __('File Attachment') }}</h5>

                                <div class="attachment d-flex flex-wrap gap-2 mt-3">
                                    @forelse ($supportTicket->attachments as $attachment)
                                        <a href="{{ $attachment->src }}" target="_blank"
                                            download="support-ticket-attachment#{{ $supportTicket->ticket_number }}">
                                            @if ($attachment->type == 'image')
                                                <img src="{{ $attachment->src }}" width="60" height="60"
                                                    alt="attachment" loading="lazy" />
                                            @else
                                                <img src="{{ asset('assets/images/pdf.png') }}" alt="attachment"
                                                    width="60" height="60" loading="lazy" />
                                            @endif
                                        </a>
                                    @empty
                                        <p>
                                            {{ __('No attachment found!') }}
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div
                            class="border rounded p-2 d-flex justify-content-between align-items-center gap-2 flex-wrap mt-3">
                            <span>
                                {{__('Customer Send Message Enable/Disable')}}
                            </span>

                            <label class="switch mb-0">
                                <a href="{{ route('admin.supportTicket.chatToggle', $supportTicket->id) }}">
                                    <input type="checkbox" {{ $supportTicket->user_chat ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </div>

                        <div class="hightighted mt-4 d-flex flex-column gap-3"></div>
                    </div>
                </div>

                <!-- messages -->
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <div class="message_wrapper h-100">

                        <div class="messages" id="messages">

                            @foreach ($supportTicket->messages as $message)
                                @php
                                    $sender = $message->sender->id == auth()->id() ? true : false;
                                    $id = $loop->index;
                                @endphp
                                <div class="message_item {{ $sender ? 'sender' : 'receiver' }}">
                                    <div class="message_item_content">

                                        @if ($sender)
                                            <div class="message_item_avatar">
                                                <img src="{{ $message->sender->thumbnail }}" alt="avatar"
                                                    loading="lazy" />
                                            </div>
                                        @endif
                                        <div class="message_item_text">
                                            <a class="pinBtn" href="{{ route('admin.supportTicket.pinMessage', $message->id) }}" title="{{ $message->is_highlighted ? 'Unpin' : 'Pin' }} this message">
                                                <i class="bi bi-pin-angle"></i>
                                            </a>

                                            <span id="textMessage{{ $id }}"></span>
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                document.getElementById('textMessage{{ $id }}').innerHTML = makeLinksClickable(
                                                    "{{ $message->message }}");
                                            });
                                        </script>

                                        @if (!$sender)
                                            <div class="message_item_avatar">
                                                <img src="{{ $message->sender->thumbnail }}" alt="avatar"
                                                    loading="lazy" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="message_date">
                                        {{ $message->created_at->format('d F, Y') }}
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        @php
                            $showSendBox = true;
                            if ($supportTicket->status == 'completed') {
                                $showSendBox = false;
                            } elseif ($supportTicket->status == 'cancel') {
                                $showSendBox = false;
                            }
                        @endphp

                        @if ($showSendBox)
                            <form class="reply-form" id="replyForm">
                                <input type="text" placeholder="{{__('Reply...')}}" class="form-control" id="message" required>
                                <button class="send-btn bg-primary btn" type="submit">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    @php
        $start_date = $supportTicket->ticket_start
            ? Carbon\Carbon::parse($supportTicket->ticket_start)->format('Y-m-d')
            : null;
        $start_time = $supportTicket->ticket_start
            ? Carbon\Carbon::parse($supportTicket->ticket_start)->format('H:i')
            : null;

        $end_date = $supportTicket->ticket_end
            ? Carbon\Carbon::parse($supportTicket->ticket_end)->format('Y-m-d')
            : null;
        $end_time = $supportTicket->ticket_end ? Carbon\Carbon::parse($supportTicket->ticket_end)->format('H:i') : null;
    @endphp


    <!-- Modal -->
    <form action="{{ route('admin.supportTicket.setScheduled', $supportTicket->id) }}" method="POST">
        @csrf
        <div class="modal fade" id="setScheduleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            {{__('Schedule for this ticket')}}
                        </h1>
                        <button type="button" class="btn-Cancel btn btn-sm" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="border rounded">
                            <div class="fz-16 border-bottom p-2">
                                {{__('Schedule Start Date and Time')}}
                            </div>
                            <div class="row p-2">
                                <div class="col-md-6">
                                    <label for="schedule">
                                        {{__('Start Date')}}
                                    </label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ $start_date }}" required />
                                    @error('start_date')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="schedule">
                                        {{__('Start Time')}}
                                    </label>
                                    <input type="time" name="start_time" class="form-control" required
                                        value="{{ $start_time }}" />
                                    @error('start_time')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border rounded mt-3">
                            <div class="fz-16 border-bottom p-2">
                                {{__('Schedule End Date and Time')}}
                            </div>
                            <div class="row p-2">
                                <div class="col-md-6">
                                    <label for="schedule">
                                        {{__('End Date')}}
                                    </label>
                                    <input type="date" name="end_date" class="form-control" required
                                        value="{{ $end_date }}" />
                                    @error('end_date')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="schedule">
                                        {{__('End Time')}}
                                    </label>
                                    <input type="time" name="end_time" class="form-control" required
                                        value="{{ $end_time }}" />
                                    @error('end_time')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border rounded mt-3 d-flex align-items-center p-2 gap-1">
                            <input type="checkbox" name="highlight" class="form-check-input" id="highlight"
                                style="width: 20px;height: 20px;">
                            <label for="highlight" class="fz-16 m-0">
                                {{__('Highlight Schedule')}}
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('Close')}}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ $start_date ? 'Update' : 'Set' }} {{__('Schedule')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            @foreach ($highlightedMessages as $message)
                var messageWithClickableLink = makeLinksClickable('{{ $message->message }}');
                $('.hightighted').append(`
                <div class="highlighted_item">
                    ${ messageWithClickableLink }
                </div>`)
            @endforeach


            scrollBottom()
        });

        channel.bind('admin-support-ticket-message-event', function(data) {
            var ticketNumber = data.ticket_number;
            if (ticketNumber == "{{ $supportTicket->ticket_number }}") {
                fetchMessages()
            }
        });

        $('#replyForm').on('submit', function(e) {
            e.preventDefault();
            const url = "{{ route('admin.supportTicket.sendMessage', $supportTicket->id) }}"
            const method = 'POST';
            const message = $('#message').val();

            if (!message) {
                return;
            }

            $.ajax({
                url: url,
                method: method,
                data: {
                    _token: "{{ csrf_token() }}",
                    message: message
                },
                success: function(response) {
                    $('#message').val('');
                    appendMessages(response.data.messages);
                }
            });
        });

        const fetchMessages = () => {
            const url = "{{ route('admin.supportTicket.fetchMessages', $supportTicket->id) }}";
            const method = 'GET';
            $.ajax({
                url: url,
                method: method,
                success: function(response) {
                    appendMessages(response.data.messages);
                }
            });
        }

        function appendMessages(messages) {
            const userId = "{{ auth()->id() }}";
            $('.messages').empty();
            messages.forEach(message => {
                const sender = (userId == message.sender_id) ? true : false;
                var messageWithClickableLink = makeLinksClickable(message.message);
                var pinLink = "{{ route('admin.supportTicket.pinMessage', ':id') }}".replace(':id',message.id);
                const messageElement = `
                    <div class="message_item ${sender ? 'sender' : 'receiver'}">
                    <div class="message_item_content">
                            <div class="message_item_avatar" style="display: ${sender ? '' : 'none'}">
                                <img src="${message.profile_photo}" alt="avatar"
                                    loading="lazy" />
                            </div>
                            <div class="message_item_text">
                                <a class="pinBtn" href="" title="${message.is_highlighted ? 'Unpin' : 'Pin' } this message">
                                    <i class="bi bi-pin-angle"></i>
                                </a>
                                ${ messageWithClickableLink }
                            </div>
                            <div class="message_item_avatar" style="display: ${sender ? 'none' : ''}">
                                <img src="${ message.profile_photo }" alt="avatar"
                                    loading="lazy" />
                            </div>
                        </div>
                        <div class="message_date">
                            ${ message.created_at }
                        </div>
                    </div>
                `;
                $('.messages').append(messageElement);
            });
            scrollBottom()
        }

        function makeLinksClickable(message) {
            // Regular expression to match URLs
            var urlRegex = /(https?:\/\/[^\s]+)/g;

            // Replace URLs with clickable links
            var messageWithLinks = message.replace(urlRegex, function(url) {
                return '<a href="' + url + '" target="_blank">' + url + '</a>';
            });

            return messageWithLinks;
        }

        const scrollBottom = () => {
            $(".messages").animate({
                scrollTop: $('.messages').prop("scrollHeight")
            }, 1);
        }
    </script>
@endpush
