<?php

namespace Modules\SupportTicket\App\Repositories;

use App\Repositories\MediaRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\SupportTicket\App\Events\SupportTicketEvent;
use Modules\SupportTicket\App\Http\Requests\SupportTicketRequest;
use Modules\SupportTicket\App\Http\Requests\TimeScheduleRequest;
use Modules\SupportTicket\App\Models\Notification;
use Modules\SupportTicket\App\Models\SupportTicket;

class SupportTicketRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return SupportTicket::class;
    }

    /**
     * find by ticket number.
     *
     * @param  $support_ticket_id
     * @return collection
     * */
    public static function findByTicketNumber($ticketNo)
    {
        return self::query()->where('ticket_number', $ticketNo)->first();
    }

    /**
     * store new banner
     *
     * */
    public static function storeByRequest(SupportTicketRequest $request): SupportTicket
    {
        $latestId = SupportTicket::max('id');
        $ticketNumber = rand(100000, 999999).($latestId + 1);

        $supportTicket = self::create([
            'user_id' => auth()->id(),
            'ticket_number' => $ticketNumber,
            'order_number' => $request->order_number,
            'issue_type' => $request->issue_type,
            'subject' => $request->subject,
            'message' => $request->message,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'pending',
        ]);

        self::attachmentFileStore($request->attachments, $supportTicket);

        $supportTicket->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        if (Schema::hasTable((new Notification())->getTable())) {
            // store notification
            $content = 'Support Ticket created from: '.auth()->user()->name.'. Ticket Number: #'.$supportTicket->ticket_number;
            Notification::create([
                'title' => 'New Support Ticket Created',
                'content' => $content,
                'url' => 'admin/support-tickets/'.$supportTicket->id,
                'icon' => 'bi-ticket-perforated-fill',
                'type' => 'info',
            ]);
        }

        try {
            SupportTicketEvent::dispatch('New Support Ticket Created');
        } catch (\Throwable $th) {
        }

        return $supportTicket;
    }

    /**
     * attachment store
     *
     **/
    public static function attachmentFileStore($attachments, $supportTicket): void
    {
        if ($attachments && is_array($attachments)) {
            foreach ($attachments as $attachment) {
                if ($attachment) {
                    $media = MediaRepository::storeByRequest($attachment, 'ticket-attachments');
                    $supportTicket->mediaAttachments()->attach($media);
                }
            }
        }
    }

    /**
     * setup or update ticket time scheduled.
     */
    public static function updateTicketTimeSchedule(SupportTicket $supportTicket, TimeScheduleRequest $request): SupportTicket
    {
        $startDate = $supportTicket->start_date;

        $supportTicket->update([
            'ticket_start' => $request->start_date.' '.$request->start_time,
            'ticket_end' => $request->end_date.' '.$request->end_time,
            'status' => $supportTicket->status == 'pending' ? 'confirm' : $supportTicket->status,
        ]);

        $message = 'Your support ticket '.($startDate ? ' scheduled has been created' : 'scheduled has been changed').'. The scheduled time is from '.Carbon::parse($request->start_date)->format('d F, Y').' '.$request->start_time.' to '.Carbon::parse($request->end_date)->format('d F, Y').' '.$request->end_time;

        $supportTicket->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $message,
            'is_highlighted' => $request->highlight ? true : false,
        ]);

        return $supportTicket;
    }

    /**
     * store message
     */
    public static function storeMessage(SupportTicket $supportTicket, Request $request): SupportTicket
    {
        $supportTicket->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return $supportTicket;
    }

    /**
     * update status
     */
    public static function updateTicketStatus(SupportTicket $supportTicket, Request $request): SupportTicket
    {
        $supportTicket->update([
            'status' => $request->status,
        ]);

        return $supportTicket;
    }
}
