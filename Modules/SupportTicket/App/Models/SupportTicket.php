<?php

namespace Modules\SupportTicket\App\Models;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    /**
     * get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get the support ticket messages
     */
    public function messages()
    {
        return $this->hasMany(SupportTicketMessage::class, 'support_ticket_id');
    }

    /**
     * get media Attachment
     */
    public function mediaAttachments()
    {
        return $this->belongsToMany(Media::class, 'support_ticket_attachments');
    }

    /**
     * get attachment
     */
    public function attachments(): Attribute
    {
        $array = [];
        foreach ($this->mediaAttachments as $media) {
            if (Storage::exists($media->src)) {
                $array[] = (object) [
                    'type' => $media->type,
                    'src' => Storage::url($media->src),
                ];
            }
        }

        return new Attribute(
            get: fn () => $array
        );
    }
}
