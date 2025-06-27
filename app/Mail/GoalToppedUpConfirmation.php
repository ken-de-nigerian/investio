<?php

namespace App\Mail;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GoalToppedUpConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Goal $goal;
    public string $top_up_amount;

    /**
     * Create a new message instance.
     */
    public function __construct(Goal $goal, string $top_up_amount)
    {
        $this->goal = $goal;
        $this->top_up_amount = $top_up_amount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Savings Goal Top-Up Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.goal_topped_up_confirmation',
            with: [
                'goal' => $this->goal,
                'top_up_amount' => $this->top_up_amount,
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
