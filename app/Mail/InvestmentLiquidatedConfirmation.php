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

class InvestmentLiquidatedConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public UserInvestment $investment;
    public string $amount;

    /**
     * Create a new message instance.
     */
    public function __construct(UserInvestment $investment, string $amount)
    {
        $this->investment = $investment;
        $this->amount = $amount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Investment Liquidated Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.investment_liquidated_confirmation',
            with: [
                'investment' => $this->investment,
                'liquidation_amount' => $this->amount,
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
