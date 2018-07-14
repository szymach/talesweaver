<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Bus\Command;

use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Integration\Doctrine\Entity\User;

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
