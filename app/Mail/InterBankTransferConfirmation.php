<?php

namespace App\Mail;

use App\Models\InterBankTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterBankTransferConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public InterBankTransfer $transfer;

    /**
     * Create a new message instance.
     */
    public function __construct(InterBankTransfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Inter Bank Transfer Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.interbank_transfer_confirmation',
            with: [
                'user' => $this->transfer->user,
                'amount' => number_format($this->transfer->amount, 2),
                'recipient' => $this->transfer->acct_name,
                'accountNumber' => $this->transfer->account_number,
                'transactionId' => $this->transfer->transfer_id,
                'date' => now()->format('M d, Y h:i A'),
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
