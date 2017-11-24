<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\User\PasswordResetToken;

class ResetPassword implements MessageCommandInterface
{
    /**
     * @var PasswordResetToken
     */
    private $token;

    /**
     * @var string
     */
    private $password;

    public function __construct(PasswordResetToken $token, string $password)
    {
        $this->token = $token;
        $this->password = $password;
    }

    public function getToken(): PasswordResetToken
    {
        return $this->token;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.reset_password.change.alert.success',
            [],
            'success'
        );
    }
}
