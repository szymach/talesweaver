<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use AppBundle\Bus\Messages\Message;
use AppBundle\Bus\Messages\MessageCommandInterface;
use AppBundle\Entity\User;

class ChangePassword implements MessageCommandInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $newPassword;

    public function __construct(User $user, string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.change_password.alert.success',
            [],
            'success'
        );
    }
}
