<?php

namespace AppBundle\Entity;

use Assert\Assert;
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
     * @var UserRole[]|Collection
     */
    private $roles;

    /**
     * @param string $username
     * @param string $password
     * @param UserRole[] $roles
     */
    public function __construct(string $username, string $password, array $roles)
    {
        Assert::thatAll($roles)->isInstanceOf(UserRole::class);

        $this->username = $username;
        $this->password = $password;
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

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
