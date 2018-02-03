<?php

declare(strict_types=1);

namespace App\Mail;

use Domain\Entity\User;

class RegistrationMailer extends AbstractUserMailer
{
    public function send(User $user): int
    {
        return $this->doSend(
            $user,
            'security.registration.mail.title',
            'security/mail/registration.html.twig',
            ['code' => $user->getActivationToken()]
        );
    }
}
