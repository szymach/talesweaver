<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use App\Bus\Messages\Message;
use App\Bus\Messages\MessageCommandInterface;
use Domain\Entity\User;

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
