<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Security;

use Talesweaver\Integration\Doctrine\Entity\User;

interface UserAccessInterface
{
    public function isAllowed(User $user): bool;
}
