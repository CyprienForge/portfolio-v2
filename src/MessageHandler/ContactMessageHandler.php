<?php

namespace App\MessageHandler;

use App\Message\ContactMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class ContactMessageHandler
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function __invoke(ContactMessage $message)
    {
        $email = (new Email())
            ->from($message->email)
            ->to('cyprien.forge@gmail.com')
            ->subject('Nouveau message de contact')
            ->html("
                <h2>Nouveau contact</h2>
                <p><strong>Nom :</strong> {$message->name}</p>
                <p><strong>Email :</strong> {$message->email}</p>
                <p><strong>Message :</strong><br>{$message->message}</p>
            ");

        $this->mailer->send($email);
    }
}
