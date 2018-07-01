<?php

declare(strict_types=1);

namespace Application\Security\Command;

use Application\Messages\Message;
use Application\Messages\MessageCommandInterface;
use Domain\User;
use DomainException;

class ActivateUser implements MessageCommandInterface
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        if ($user->isActive()) {
            throw new DomainException(
                sprintf('User "%s" is already active!', $user->getId())
            );
        }

        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMessage(): Message
    {
        return new Message(
            'security.activation.alert.success',
            ['%username%' => $this->user->getUsername()],
            'success'
        );
    }
}