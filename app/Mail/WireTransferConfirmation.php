<?php

namespace App\Mail;

use App\Models\WireTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WireTransferConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public WireTransfer $wireTransfer;

    /**
     * Create a new message instance.
     */
    public function __construct(WireTransfer $wireTransfer)
    {
        $this->wireTransfer = $wireTransfer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Domestic Wire Transfer Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.wire-transfer-confirmation',
            with: [
                'transfer' => $this->wireTransfer,
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
