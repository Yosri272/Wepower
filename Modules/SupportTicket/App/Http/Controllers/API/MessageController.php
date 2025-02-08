<?php

namespace Modules\SupportTicket\App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SupportTicket\App\Events\AdminSupportTicketMessageEvent;
use Modules\SupportTicket\App\Http\Requests\SupportTicketMessageRequest;
use Modules\SupportTicket\App\Repositories\SupportTicketMessageRepository;
use Modules\SupportTicket\App\Repositories\SupportTicketRepository;
use Modules\SupportTicket\App\resources\SupportTicketMessageResource;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate(['ticket_number' => 'required|exists:support_tickets,ticket_number']);

        $supportTicket = SupportTicketRepository::findByTicketNumber($request->ticket_number);

        return $this->json('Support ticket messages', [
            'messages' => SupportTicketMessageResource::collection($supportTicket->messages),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupportTicketMessageRequest $request)
    {
        $supportTicket = SupportTicketRepository::query()->whereTicketNumber($request->ticket_number)->first();

        if (! $supportTicket) {
            return $this->json('Sorry!, Support ticket not found', [], 422);
        }

        if (! $supportTicket->user_chat || $supportTicket->status == 'Completed' || $supportTicket->status == 'cancel') {
            return $this->json('Sorry!, Message send is not allowed for this support ticket', [], 422);
        }

        SupportTicketMessageRepository::storeByRequest($request, $supportTicket);

        try {
            AdminSupportTicketMessageEvent::dispatch($request->ticket_number);
        } catch (\Throwable $th) {
        }

        return $this->json('message sent successfully', [
            'messages' => SupportTicketMessageResource::collection($supportTicket->messages),
        ]);
    }
}
