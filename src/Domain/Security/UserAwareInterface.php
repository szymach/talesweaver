<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Security;

use Talesweaver\Domain\User;

interface UserAwareInterface
{
    public function setUser(User $user): void;
    public function getUser(): User;
}
