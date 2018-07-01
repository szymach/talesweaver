<?php

declare(strict_types=1);

namespace Application\Security;

use Domain\User;

interface UserAwareInterface
{
    public function setUser(User $user): void;
    public function getUser(): User;
}
