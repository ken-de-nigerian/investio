<?php

namespace App\Notifications;

use App\Models\Deposit;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDepositNotification extends Notification
{

    public Deposit $deposit;

    /**
     * Create a new notification instance.
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
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
            ->subject('New Deposit Request - Action Required')
            ->view('emails.new_deposit_notification', [
                'deposit' => $this->deposit
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
            'deposit_id' => $this->deposit->id,
            'amount' => $this->deposit->amount,
            'message' => 'New deposit request pending approval',
        ];
    }
}
