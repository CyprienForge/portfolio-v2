<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

final class ContactMessage
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $subject,
        public readonly string $message,
    ) {}
}
