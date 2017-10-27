<?php

namespace AppBundle\Entity;

use AppBundle\Security\CodeGenerator\ActivationCodeGenerator;
use Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
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
     * @var boolean
     */
    private $active = false;

    /**
     * @var UserActivationCode[]|Collection
     */
    private $activationCodes;

    /**
     * @param string $username
     * @param string $password
     * @param UserRole[] $roles
     * @param ActivationCodeGenerator $generator
     */
    public function __construct(
        string $username,
        string $password,
        array $roles,
        ActivationCodeGenerator $generator
    ) {
        Assert::thatAll($roles)->isInstanceOf(UserRole::class);

        $this->username = $username;
        $this->password = $password;
        $this->roles = new ArrayCollection($roles);
        $this->activationCodes = new ArrayCollection([$generator->generate($this)]);
    }

    public function getId(): int
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): void
    {
        if ($this->active) {
            throw new DomainException(sprintf('User "%s" is already active!', $this->id));
        }

        $this->active = true;
    }

    public function getActivationCode(): ?UserActivationCode
    {
        return $this->activationCodes->count() > 0
            ? $this->activationCodes->first()
            : null
        ;
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
