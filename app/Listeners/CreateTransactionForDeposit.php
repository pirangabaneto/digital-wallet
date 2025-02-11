<?php

namespace App\Listeners;

use App\Events\DepositMade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transaction;

class CreateTransactionForDeposit
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
        Transaction::create([
            'wallet_id' => $event->wallet->id,
            'to_wallet_id' => $event->wallet->id,
            'type' => $event->type,
            'amount' => $event->amount,
        ]);
    }
}
