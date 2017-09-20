<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var UserRole[]|Collection
     */
    private $roles;

    /**
     * @param string $username
     * @param string $password
     * @param string $salt
     * @param UserRole[] $roles
     */
    public function __construct(string $username, string $password, string $salt, array $roles)
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = new ArrayCollection($roles);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        $this->username = null;
        $this->password = null;
    }
}
