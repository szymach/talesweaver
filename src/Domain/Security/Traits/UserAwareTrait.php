<?php

declare(strict_types=1);

namespace Domain\Security\Traits;

use AppBundle\Entity\User;

trait UserAwareTrait
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
