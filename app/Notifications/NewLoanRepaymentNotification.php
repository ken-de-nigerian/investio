<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLoanRepaymentNotification extends Notification
{
    use Queueable;

    public Loan $loan;
    public string $amount;

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan, string $amount)
    {
        $this->loan = $loan;
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
            ->subject('New Deposit Request - Action Required')
            ->view('emails.new_loan_repayment_notification', [
                'loan' => $this->loan,
                'amount' => $this->amount,
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
            'loan_id' => $this->loan->id,
            'amount' => $this->amount,
            'message' => 'New loan repayment notification',
        ];
    }
}
