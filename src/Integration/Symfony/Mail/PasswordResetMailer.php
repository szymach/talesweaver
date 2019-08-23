<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Mail;

use Assert\Assertion;
use Talesweaver\Domain\Author;

class PasswordResetMailer extends AbstractAuthorMailer
{
    public function send(Author $author): bool
    {
        $code = $author->getPasswordResetToken();
        Assertion::notNull($code, "Author \"{$author->getId()->toString()}\" has no password reset token.");

        return $this->doSend(
            $author,
            'security.reset_password.mail.title',
            'security/mail/passwordReset.html.twig',
            ['code' => $code]
        );
    }
}
