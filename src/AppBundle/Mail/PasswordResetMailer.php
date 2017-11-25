<?php

declare(strict_types=1);

namespace AppBundle\Mail;

use AppBundle\Entity\User;

class PasswordResetMailer extends AbstractUserMailer
{
    public function send(User $user): int
    {
        return $this->doSend(
            $user,
            'security.reset_password.mail.title',
            'security/mail/passwordReset.html.twig',
            ['code' => $user->getPasswordResetToken()]
        );
    }
}