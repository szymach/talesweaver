<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;

interface UserAwareInterface
{
    /**
     * @param User $user
     */
    public function setUser(User $user) : void;

    /**
     * @return User
     */
    public function getUser(): User;
}
