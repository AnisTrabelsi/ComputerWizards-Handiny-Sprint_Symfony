<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $replyTo;

    public function __construct(private MailerInterface $mailer, string $replyTo = 'noreply@example.com')
    {
        $this->replyTo = $replyTo;
    }

    public function sendEmail(
        $to = 'chaima.lotfi@esprit.tn',
        $content = '<p>See Twig integration for better HTML integration!</p>',
        $subject = 'Demande de réservation de voiture'
    ): void
    {
        $email = (new Email())
            ->from('chaima.lotfi@esen.tn')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
//            ->text('Sending emails is fun again!')
            ->html($content);
        $this->mailer->send($email);
        // ...
    }
}
