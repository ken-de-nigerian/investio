<?php

namespace App\Notifications;

use App\Models\UserInvestment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInvestmentPurchaseNotification extends Notification
{
    use Queueable;

    public UserInvestment $investment;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserInvestment $investment)
    {
        $this->investment = $investment;
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
            ->subject('New Investment Purchase')
            ->view('emails.new_investment_purchase_notification', [
                'investment' => $this->investment,
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
            'investment_id' => $this->investment->id,
            'amount' => $this->investment->amount,
            'message' => 'New investment purchase notification',
        ];
    }
}
