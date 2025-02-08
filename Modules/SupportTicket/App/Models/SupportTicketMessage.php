<?php

namespace Modules\SupportTicket\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    /**
     * get sender
     *
     * @return BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * get ticket
     *
     * @return BelongsTo
     */
    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }
}
