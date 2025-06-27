<?php

namespace App\Notifications;

use App\Models\WireTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewWireTransferNotification extends Notification
{
    use Queueable;

    public WireTransfer $wireTransfer;

    /**
     * Create a new notification instance.
     */
    public function __construct(WireTransfer $wireTransfer)
    {
        $this->wireTransfer = $wireTransfer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Domestic Transfer Alert')
            ->view('emails.new_wire_transfer_notification', [
                'transfer' => $this->wireTransfer,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->wireTransfer->id,
            'amount' => $this->wireTransfer->amount,
            'message' => 'New wire transfer request pending approval',
        ];
    }
}
