<?php

namespace App\Listeners;

use App\Events\SendOTPMail as EventsSendOTPMail;
use App\Mail\SendOTP;
use Illuminate\Support\Facades\Mail;

class SendOTPMailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventsSendOTPMail $event): void
    {
        if (config('app.mail_username') && config('app.mail_password') && config('app.mail_encryption')) {
            Mail::to($event->email)->send(new SendOTP($event->email, $event->message));
        }
    }
}
