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
            ->from('contact@cyprien-forge.fr')
            ->replyTo($message->email)
            ->to('cyprien.forge.pro@gmail.com')
            ->subject(sprintf('[Contact Site] %s', $message->subject))
            ->text($this->getTextContent($message)) // Ajoutez une version texte
            ->html($this->getHtmlContent($message));
            // Ajoutez des headers anti-spam
        $email->getHeaders()
                ->addTextHeader('X-Mailer', 'Symfony Mailer')
                ->addTextHeader('X-Priority', '3')
                ->addTextHeader('Importance', 'Normal');

        $this->mailer->send($email);
    }

    private function getTextContent(ContactMessage $message): string
    {
        return <<<TEXT
Nouveau message de contact

Nom: {$message->name}
Email: {$message->email}
Sujet: {$message->subject}

Message:
{$message->message}

---
Ce message a été envoyé depuis le formulaire de contact de cyprien-forge.fr
TEXT;
    }

    private function getHtmlContent(ContactMessage $message): string
    {
        $name = htmlspecialchars($message->name, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($message->email, ENT_QUOTES, 'UTF-8');
        $subject = htmlspecialchars($message->subject, ENT_QUOTES, 'UTF-8');
        $msg = nl2br(htmlspecialchars($message->message, ENT_QUOTES, 'UTF-8'));

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f4f4f4; padding: 20px; border-radius: 5px;">
        <h2 style="color: #2c3e50; margin-top: 0;">📧 Nouveau message de contact</h2>
        
        <div style="background-color: white; padding: 15px; border-radius: 5px; margin: 15px 0;">
            <p style="margin: 10px 0;">
                <strong style="color: #2c3e50;">Nom :</strong><br>
                {$name}
            </p>
            
            <p style="margin: 10px 0;">
                <strong style="color: #2c3e50;">Email :</strong><br>
                <a href="mailto:{$email}" style="color: #3498db;">{$email}</a>
            </p>
            
            <p style="margin: 10px 0;">
                <strong style="color: #2c3e50;">Sujet :</strong><br>
                {$subject}
            </p>
            
            <p style="margin: 10px 0;">
                <strong style="color: #2c3e50;">Message :</strong><br>
                {$msg}
            </p>
        </div>
        
        <p style="color: #7f8c8d; font-size: 12px; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px;">
            Ce message a été envoyé depuis le formulaire de contact de 
            <a href="https://cyprien-forge.fr" style="color: #3498db;">cyprien-forge.fr</a>
        </p>
    </div>
</body>
</html>
HTML;
    }
}
