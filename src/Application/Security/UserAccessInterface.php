<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

use Talesweaver\Domain\User;

interface UserAccessInterface
{
    public function isAllowed(User $user): bool;
}
