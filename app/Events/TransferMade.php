<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Wallet;

class TransferMade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fromWallet;
    public $toWallet;
    public $amount;

    /**
     * Create a new event instance.
     */
    public function __construct(Wallet $fromWallet, Wallet $toWallet, $amount)
    {
        $this->fromWallet = $fromWallet;
        $this->toWallet = $toWallet;
        $this->amount = $amount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
