<?php

namespace App\Notifications;

use App\Models\UserInvestment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewInvestmentLiquidatedNotification extends Notification
{
    use Queueable;

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
            ->subject('New Investment Liquidation')
            ->view('emails.new_investment_liquidated_notification', [
                'investment' => $this->investment,
                'liquidated_amount' => $this->amount,
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
            'amount' => $this->amount,
            'message' => 'New investment liquidated notification',
        ];
    }
}
