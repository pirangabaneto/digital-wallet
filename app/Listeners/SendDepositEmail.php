<?php

namespace App\Listeners;

use App\Events\DepositMade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepositEmail;
use Illuminate\Support\Facades\Auth;

class SendDepositEmail
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
    public function handle(DepositMade $event): void
    {
        Mail::to(Auth::user()->email)->send(new DepositEmail(Auth::user()->name, $event->amount, $event->wallet->id));
    }
}
