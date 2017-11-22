<?php

declare(strict_types=1);

namespace Domain\Security;

use AppBundle\Entity\User;

interface UserAccessInterface
{
    public function isAllowed(User $user): bool;
}
