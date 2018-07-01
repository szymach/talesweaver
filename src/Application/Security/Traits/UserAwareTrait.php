<?php

declare(strict_types=1);

namespace Application\Security\Traits;

use Domain\User;

trait UserAwareTrait
{
    /**
     * @var User
     */
    private $user;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}