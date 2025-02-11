<?php

namespace App\Listeners;

use App\Events\TransferMade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transaction;

class CreateTransactionForTransfer
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
        Transaction::create([
            'wallet_id' => $event->fromWallet->id,
            'to_wallet_id' => $event->toWallet->id,
            'type' => 'transfer',
            'amount' => $event->amount,
        ]);
    }
}
