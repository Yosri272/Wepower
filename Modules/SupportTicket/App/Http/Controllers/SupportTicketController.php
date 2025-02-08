<?php

namespace Modules\SupportTicket\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SupportTicket\App\Events\SupportTicketMessageEvent;
use Modules\SupportTicket\App\Http\Requests\TimeScheduleRequest;
use Modules\SupportTicket\App\Models\SupportTicket;
use Modules\SupportTicket\App\Models\SupportTicketMessage;
use Modules\SupportTicket\App\Repositories\SupportTicketRepository;
use Modules\SupportTicket\App\resources\SupportTicketMessageResource;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request()->status;

        $supportTickets = SupportTicketRepository::query()->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->latest('id')->paginate(10);

        return view('supportticket::index', compact('supportTickets'));
    }

    /**
     * Show the specified resource.
     */
    public function show(SupportTicket $supportTicket)
    {
        $highlightedMessages = $supportTicket->messages()->where(function ($query) {
            $query->where('is_highlighted', true);
        })->get();

        return view('supportticket::show', compact('supportTicket', 'highlightedMessages'));
    }

    /**
     * setup ticket time scheduled.
     */
    public function setScheduled(SupportTicket $supportTicket, TimeScheduleRequest $request)
    {
        SupportTicketRepository::updateTicketTimeSchedule($supportTicket, $request);

        try {
            SupportTicketMessageEvent::dispatch($supportTicket->ticket_number);
        } catch (\Throwable $th) {
        }

        return back()->withSuccess('Ticket time schedule updated successfully');
    }

    /**
     * Send message to the specified support ticket.
     */
    public function sendMessage(Request $request, SupportTicket $supportTicket)
    {
        SupportTicketRepository::storeMessage($supportTicket, $request);

        try {
            SupportTicketMessageEvent::dispatch($supportTicket->ticket_number);
        } catch (\Throwable $th) {
        }

        return $this->json('message sent successfully', [
            'messages' => SupportTicketMessageResource::collection($supportTicket->messages),
        ]);
    }

    /**
     * fetch messages
     */
    public function fetchMessages(SupportTicket $supportTicket)
    {
        return $this->json('all messages', [
            'messages' => SupportTicketMessageResource::collection($supportTicket->messages),
        ]);
    }

    /**
     * update status.
     */
    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        SupportTicketRepository::updateTicketStatus($supportTicket, $request);

        try {
            SupportTicketMessageEvent::dispatch($supportTicket->ticket_number);
        } catch (\Throwable $th) {
        }

        return back()->withSuccess('Ticket status updated successfully');
    }

    /**
     * toggle user chat
     */
    public function chatToggle(SupportTicket $supportTicket)
    {
        $supportTicket->update([
            'user_chat' => ! $supportTicket->user_chat,
        ]);

        try {
            SupportTicketMessageEvent::dispatch($supportTicket->ticket_number);
        } catch (\Throwable $th) {
        }

        return back()->withSuccess('Customer Message toggle updated');
    }

    /**
     * pin message
     */
    public function pinMessage(SupportTicketMessage $supportTicketMessage)
    {
        $supportTicketMessage->update([
            'is_highlighted' => ! $supportTicketMessage->is_highlighted,
        ]);

        try {
            SupportTicketMessageEvent::dispatch($supportTicketMessage->supportTicket->ticket_number);
        } catch (\Throwable $th) {
        }

        if (! $supportTicketMessage->is_highlighted) {
            $message = 'The message has been pinned successfully.';
        } else {
            $message = 'The message has been unpinned successfully';
        }

        return back()->withSuccess($message);
    }
}
