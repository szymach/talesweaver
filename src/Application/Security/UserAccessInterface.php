<?php

declare(strict_types=1);

namespace Application\Security;

use Domain\User;

interface UserAccessInterface
{
    public function isAllowed(User $user): bool;
}
