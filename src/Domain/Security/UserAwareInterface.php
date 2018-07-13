<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Security;

use Talesweaver\Integration\Doctrine\Entity\User;

interface UserAwareInterface
{
    public function setUser(User $user): void;
    public function getUser(): User;
}
