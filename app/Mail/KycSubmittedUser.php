<?php

namespace App\Mail;

use App\Models\Kyc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KycSubmittedUser extends Mailable
{
    use Queueable, SerializesModels;

    public Kyc $kycData;

    /**
     * Create a new message instance.
     */
    public function __construct(Kyc $kycData)
    {
        $this->kycData = $kycData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your KYC Submission Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.kyc_user_submitted',
            with: [
                'user' => $this->kycData->user,
                'kyc' => $this->kycData
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
