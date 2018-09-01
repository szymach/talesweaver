<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Mail;

use Talesweaver\Domain\Author;

class RegistrationMailer extends AbstractAuthorMailer
{
    public function send(Author $author): bool
    {
        return $this->doSend(
            $author,
            'security.registration.mail.title',
            'security/mail/registration.html.twig',
            ['code' => $author->getActivationToken()]
        );
    }
}
