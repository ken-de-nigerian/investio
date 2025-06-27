<?php

namespace App\Notifications;

use App\Models\InterBankTransfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInterBankTransferNotification extends Notification
{
    use Queueable;

    public InterBankTransfer $transfer;

    /**
     * Create a new message instance.
     */
    public function __construct(InterBankTransfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Inter-Bank Transfer Processed')
            ->view('emails.new_interbank_transfer_notification', [
                'amount' => number_format($this->transfer->amount, 2),
                'sender' => $this->transfer->user->first_name . ' ' . $this->transfer->user->last_name,
                'recipient' => $this->transfer->acct_name,
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
            'transfer_id' => $this->transfer->id,
            'amount' => $this->transfer->amount,
            'message' => 'New interbank transfer request pending approval',
        ];
    }
}
