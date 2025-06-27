<?php

namespace App\Notifications;

use App\Models\Kyc;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewKycNotification extends Notification
{

    public Kyc $kycData;

    /**
     * Create a new notification instance.
     */
    public function __construct(Kyc $kycData)
    {
        $this->kycData = $kycData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Add other channels as needed
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New KYC Submission Requires Review')
            ->view('emails.kyc_admin_submitted', [
                'user' => $this->kycData->user,
                'kyc' => $this->kycData
            ]);
    }

    /**
     * Get the array representation of the notification (for database).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'kyc_id' => $this->kycData->id,
            'user_id' => $this->kycData->user_id,
            'status' => $this->kycData->status,
            'message' => 'New KYC submission requires review',
            'action_url' => url("/admin/kyc/{$this->kycData->id}"),
        ];
    }
}
