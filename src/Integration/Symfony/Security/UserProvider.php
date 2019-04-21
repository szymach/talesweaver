<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Security;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Query\Security\AuthorByEmail;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Symfony\Security\User;
use Throwable;

final class UserProvider implements UserProviderInterface
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param string|null $username
     * @return UserInterface
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username): UserInterface
    {
        if ('' === $username || null === $username) {
            throw new UsernameNotFoundException('No username provided.');
        }

        try {
            $email = new Email($username);
        } catch (Throwable $ex) {
            throw new UsernameNotFoundException("\"{$username}\" is not a valid email.");
        }

        $user = $this->queryBus->query(new AuthorByEmail($email));
        if (null === $user) {
            throw new UsernameNotFoundException("Username \"{$username}\" does not exist.");
        }

        return new User($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
