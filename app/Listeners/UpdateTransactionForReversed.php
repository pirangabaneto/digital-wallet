<?php

namespace App\Listeners;

use App\Events\TransactionReversed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateTransactionForReversed
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
        $event->transaction->update(['type' => $event->type]);
        $event->transaction->save();
    }
}
