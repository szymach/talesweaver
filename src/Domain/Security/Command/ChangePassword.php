<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use AppBundle\Entity\User\PasswordResetToken;

class ChangePassword
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
}
