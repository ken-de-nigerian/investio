<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FundsManagedConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $amount;
    public string $type;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $amount, $type)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fund ' . ucfirst($this->type) . ' Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.funds_managed_confirmation',
            with: [
                'user' => $this->user,
                'amount' => $this->amount,
                'type' => $this->type,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
