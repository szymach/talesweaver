<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;

class UserRole extends Role
{
    const USER = 'ROLE_USER';

    /**
     * @var string
     */
    private $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function __toString()
    {
        return $this->role;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
