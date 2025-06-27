<?php

namespace App\Mail;

use App\Models\UserInvestment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestmentPurchaseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public UserInvestment $userInvestment;

    /**
     * Create a new message instance.
     */
    public function __construct(UserInvestment $userInvestment)
    {
        $this->userInvestment = $userInvestment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Investment Purchase Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.investment_purchase_confirmation',
            with: [
                'investment' => $this->userInvestment,
                'user' => $this->userInvestment->user
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
