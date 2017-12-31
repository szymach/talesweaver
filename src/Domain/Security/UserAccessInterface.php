<?php

declare(strict_types=1);

namespace Domain\Security;

use App\Entity\User;

interface UserAccessInterface
{
    public function isAllowed(User $user): bool;
}
