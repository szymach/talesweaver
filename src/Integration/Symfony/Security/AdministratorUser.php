<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Talesweaver\Domain\Administrator;

final class AdministratorUser implements UserInterface
{
    public const ROLE = 'ROLE_ADMIN';

    /**
     * @var Administrator
     */
    private $administrator;

    /**
     * @var array
     */
    private $roles;

    public function __construct(Administrator $administrator)
    {
        $this->administrator = $administrator;
        $this->roles = [self::ROLE];
    }

    public function __toString()
    {
        return (string) $this->administrator->getEmail();
    }

    public function getAdministrator(): Administrator
    {
        return $this->administrator;
    }

    public function getUsername(): string
    {
        return (string) $this->administrator->getEmail();
    }

    public function getPassword(): string
    {
        return $this->administrator->getPassword();
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
