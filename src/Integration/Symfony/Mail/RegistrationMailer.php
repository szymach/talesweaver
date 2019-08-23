<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Mail;

use Assert\Assertion;
use Talesweaver\Domain\Author;

class RegistrationMailer extends AbstractAuthorMailer
{
    public function send(Author $author): bool
    {
        $code = $author->getActivationToken();
        Assertion::notNull($code, "Author \"{$author->getId()->toString()}\" has no activation token.");

        return $this->doSend(
            $author,
            'security.registration.mail.title',
            'security/mail/registration.html.twig',
            ['code' => $code]
        );
    }
}
