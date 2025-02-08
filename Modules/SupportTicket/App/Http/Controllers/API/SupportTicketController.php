<?php

namespace Modules\SupportTicket\App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SupportTicket\App\Http\Requests\SupportTicketRequest;
use Modules\SupportTicket\App\Models\SupportTicket;
use Modules\SupportTicket\App\Repositories\SupportTicketRepository;
use Modules\SupportTicket\App\resources\SupportTicketDetailsResource;
use Modules\SupportTicket\App\resources\SupportTicketMessageResource;
use Modules\SupportTicket\App\resources\SupportTicketResource;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $status = $request->status;

        $supportTickets = SupportTicket::where('user_id', auth()->id())
            ->when($status == 'running', function ($query) {
                return $query->where('status', 'pending')->orWhere('status', 'confirm');
            })->when($status == 'completed', function ($query) {
                return $query->where('status', 'completed');
            })->when($status == 'cancel', function ($query) {
                return $query->where('status', 'cancel');
            });

        $totalTickets = $supportTickets->count();

        $supportTickets = $supportTickets->when($skip, function ($query) use ($skip) {
            return $query->skip($skip);
        })->when($perPage, function ($query) use ($perPage) {
            return $query->take($perPage);
        })->orderBy('id', 'desc')->get();

        $runningTickets = SupportTicket::where('user_id', auth()->id())->where('status', 'pending')->orWhere('status', 'confirm')->count();

        $completedTickets = SupportTicket::where('user_id', auth()->id())->where('status', 'completed')->count();

        $cancelTickets = SupportTicket::where('user_id', auth()->id())->where('status', 'cancel')->count();

        return $this->json('support ticket list', [
            'total' => $totalTickets,
            'running' => $runningTickets,
            'completed' => $completedTickets,
            'cancel' => $cancelTickets,
            'support_tickets' => SupportTicketResource::collection($supportTickets),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupportTicketRequest $request)
    {
        $supportTicket = SupportTicketRepository::storeByRequest($request);

        return $this->json('support ticket created successfully', [
            'support_ticket' => SupportTicketResource::make($supportTicket),
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request)
    {
        $ticketNumber = $request->ticket_number;

        $supportTicket = SupportTicketRepository::query()->where('user_id', auth()->id())->where('ticket_number', $ticketNumber)->first();

        if (! $supportTicket) {
            return $this->json('invalid ticket number', [], 422);
        }

        $highlightedMessages = $supportTicket->messages()->where(function ($query) {
            $query->where('is_highlighted', true);
        })->get();

        return $this->json('support ticket details', [
            'support_ticket' => SupportTicketDetailsResource::make($supportTicket),
            'highlighted_messages' => SupportTicketMessageResource::collection($highlightedMessages),
        ]);
    }
}
