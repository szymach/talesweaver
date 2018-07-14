<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Command;

use DomainException;
use Talesweaver\Application\Messages\Message;
use Talesweaver\Application\Messages\MessageCommandInterface;
use Talesweaver\Integration\Doctrine\Entity\User;

class ActivateUser implements MessageCommandInterface
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        if (true === $user->isActive()) {
            throw new DomainException(sprintf('User "%s" is already active!', $user->getId()));
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
