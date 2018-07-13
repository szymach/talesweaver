<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security\Traits;

use Talesweaver\Integration\Doctrine\Entity\User;

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
