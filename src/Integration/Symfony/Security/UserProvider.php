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

class UserProvider implements UserProviderInterface
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function loadUserByUsername($username): UserInterface
    {
        if ('' === $username) {
            throw new UsernameNotFoundException('No username provided.');
        }

        $user = $this->queryBus->query(new AuthorByEmail(new Email($username)));
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
