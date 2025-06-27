<?php

namespace App\Mail\Transports;

use RuntimeException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\Envelope;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PhpMailerTransport implements TransportInterface
{
    /**
     * Send the email using PHPMailer.
     *
     * @param RawMessage $message
     * @param Envelope|null $envelope
     * @return SentMessage|null
     * @throws RuntimeException If the email fails to send.
     */
    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        // Get the Symfony Email object from the RawMessage
        if ($message instanceof Email) {
            // Initialize PHPMailer
            $mailer = new PHPMailer(true);

            try {
                // Configure PHPMailer
                $mailer->isSMTP();
                $mailer->Host = config('mail.mailers.phpmailer.host', 'default-host');
                $mailer->SMTPAuth = true;
                $mailer->Username = config('mail.mailers.phpmailer.username');
                $mailer->Password = config('mail.mailers.phpmailer.password');
                $mailer->SMTPSecure = config('mail.mailers.phpmailer.encryption', PHPMailer::ENCRYPTION_STARTTLS);
                $mailer->Port = config('mail.mailers.phpmailer.port');

                // Set email details
                $mailer->setFrom($message->getFrom()[0]->getAddress(), $message->getFrom()[0]->getName());
                foreach ($message->getTo() as $to) {
                    $mailer->addAddress($to->getAddress(), $to->getName());
                }
                $mailer->Subject = $message->getSubject();

                // Set the email body
                $textBody = $message->getTextBody();
                $htmlBody = $message->getHtmlBody();

                if ($htmlBody) {
                    // If HTML body is available, use it
                    $mailer->isHTML();
                    $mailer->Body = $htmlBody;
                    $mailer->AltBody = $textBody ?? strip_tags($htmlBody); // Fallback to plain text
                } else {
                    // Use plain text body
                    $mailer->isHTML(false);
                    $mailer->Body = $textBody;
                }

                // Send the email
                $mailer->send();
            } catch (PHPMailerException) {
                // Throw an exception if the email fails to send
                throw new RuntimeException('Failed to send email: ' . $mailer->ErrorInfo);
            }
        }

        // Return null to comply with the interface
        return null;
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'phpmailer';
    }
}
