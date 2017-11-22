<?php

declare(strict_types=1);

namespace Domain\Security;

use AppBundle\Entity\User;

interface UserAwareInterface
{
    /**
     * @param User $user
     */
    public function setUser(User $user): void;

    /**
     * @return User
     */
    public function getUser(): User;
}
