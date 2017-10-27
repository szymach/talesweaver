<?php

declare(strict_types=1);

namespace Domain\Security\Command;

use AppBundle\Entity\User;
use DomainException;

class ActivateUser
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
}
