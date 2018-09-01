<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Mail;

use Talesweaver\Domain\Author;

class PasswordResetMailer extends AbstractAuthorMailer
{
    public function send(Author $author): bool
    {
        return $this->doSend(
            $author,
            'security.reset_password.mail.title',
            'security/mail/passwordReset.html.twig',
            ['code' => $author->getPasswordResetToken()]
        );
    }
}
