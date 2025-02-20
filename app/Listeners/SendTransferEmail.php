<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TransferMade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TransferEmail;

class SendTransferEmail
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
    public function handle(TransferMade $event): void
    {
        Mail::to(Auth::user()->email)->send(new TransferEmail(Auth::user()->name, $event->amount, $event->fromWallet->id, $event->toWallet->id));
    }
}
