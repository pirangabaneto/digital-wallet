<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Mail\Mailables\Address;

class TransactionReversedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $amount;
    public $transactionId;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, float $amount, int $transactionId)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->transactionId = $transactionId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaction Reversed - PirasWallet',
            from: new Address('pirangabaneto@gmail.com', 'PirasWallet'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reverse',
            with: [
                'name' => $this->name,
                'amount' => $this->amount,
                'transactionId' => $this->transactionId,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
