<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Doctrine\Entity\User;
use Talesweaver\Integration\Doctrine\Repository\UserRepository;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loadUserByUsername($username): UserInterface
    {
        if (null === $username) {
            throw new UsernameNotFoundException('No username provided.');
        }

        $user = $this->repository->findOneByEmail(new Email($username));
        if (null === $user) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->repository->findOneByEmail(new Email($user->getUsername()));
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
