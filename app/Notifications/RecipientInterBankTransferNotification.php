<?php

namespace App\Notifications;

use App\Models\InterBankTransfer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecipientInterBankTransferNotification extends Notification
{
    use Queueable;

    public InterBankTransfer $transfer;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(InterBankTransfer $transfer, User $user)
    {
        $this->transfer = $transfer;
        $this->user = $user;
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
            ->subject('You Received a Transfer')
            ->view('emails.recipient_new_interbank_transfer_notification', [
                'user' => $this->user,
                'amount' => number_format($this->transfer->amount, 2),
                'sender' => $this->transfer->user->first_name . ' ' . $this->transfer->user->last_name,
                'accountNumber' => $this->transfer->account_number,
                'transactionId' => $this->transfer->transfer_id,
                'date' => now()->format('M d, Y h:i A'),
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
            //
        ];
    }
}
