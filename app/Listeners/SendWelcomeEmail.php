<?php

namespace App\Listeners;

use App\Events\WelcomeEmailSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class SendWelcomeEmail
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
    public function handle(WelcomeEmailSent $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}
