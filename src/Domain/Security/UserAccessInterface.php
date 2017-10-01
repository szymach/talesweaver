<?php

namespace Domain\Security;

use AppBundle\Entity\User;

interface UserAccessInterface
{
    public function isAllowed(User $user) : bool;
}
