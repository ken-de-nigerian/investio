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

class PasswordResetConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $ip;
    public string $device;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $ip, string $device)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->device = $device;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-confirmation',
            with: [
                'user' => $this->user,
                'ip' => $this->ip,
                'device' => $this->device,
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
