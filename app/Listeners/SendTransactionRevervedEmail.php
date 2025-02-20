<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TransactionReversed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TransactionReversedEmail;

class SendTransactionRevervedEmail
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
    public function handle(TransactionReversed $event): void
    {
        Mail::to(Auth::user()->email)->send(new TransactionReversedEmail(Auth::user()->name, $event->transaction->amount, $event->transaction->id));
    }
}
