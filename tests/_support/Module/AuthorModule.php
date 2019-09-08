<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Security\ActivateAuthor;
use Talesweaver\Application\Command\Security\CreateAuthor;
use Talesweaver\Application\Query\Security\AuthorByEmail;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Security\User;
use Talesweaver\Tests\Query\Security\TokenByAuthor;

final class AuthorModule extends Module
{
    public const AUTHOR_EMAIL = 'test@example.com';
    public const AUTHOR_PASSWORD = 'password123';
    public const AUTHOR_ROLE = 'ROLE_USER';
    public const LOCALE = 'pl';
    private const FIREWALL = 'main';

    /**
     * @var Symfony
     */
    private $symfony;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * phpcs:disable
     */
    public function _before(TestInterface $test)
    {
        $this->symfony = $this->getModule('Symfony');
        /* @var $container ContainerModule */
        $container = $this->getModule(ContainerModule::class);
        $this->commandBus = $container->getService(CommandBus::class);
        $this->queryBus = $container->getService(QueryBus::class);
    }

    public function loginAsUser(
        string $email = self::AUTHOR_EMAIL,
        string $password = self::AUTHOR_PASSWORD,
        ?string $name = null,
        ?string $surname = null
    ): void {
        $user = new User($this->getAuthor($email, $password, true, $name, $surname));
        $token = new UsernamePasswordToken(
            $user,
            $password,
            self::FIREWALL,
            $user->getRoles()
        );
        $this->getTokenStorage()->setToken($token);

        $session = $this->getSession();
        $session->set(sprintf('_security_%s', self::FIREWALL), serialize($token));
        $session->save();

        $this->symfony->setCookie($session->getName(), $session->getId());
    }

    public function getAuthor(
        string $email = self::AUTHOR_EMAIL,
        string $password = self::AUTHOR_PASSWORD,
        bool $active = true,
        ?string $name = null,
        ?string $surname = null
    ): Author {
        $emailVO = new Email($email);
        $author = $this->queryBus->query(new AuthorByEmail($emailVO));
        if (null === $author) {
            $this->commandBus->dispatch(
                new CreateAuthor(
                    new Email($email),
                    $password,
                    ShortText::nullableFromString($name),
                    ShortText::nullableFromString($surname)
                )
            );

            $author = $this->queryBus->query(new AuthorByEmail($emailVO));
            if (true === $active) {
                $this->commandBus->dispatch(new ActivateAuthor($author));
            }
        }

        return $author;
    }

    public function seeNewAuthorHasBeenCreated(string $email, ?string $name = null, ?string $surname = null): void
    {
        $author = $this->canSeeAuthorExists($email, $name, $surname);
        $this->assertFalse($author->isActive());

        $activationToken = $author->getActivationToken();
        $this->assertNotNull($activationToken);
        $this->assertTrue($activationToken->isValid());
    }

    public function canSeeAuthorExists(string $email, ?string $name = null, ?string $surname = null): Author
    {
        /** @var Author $author */
        $author = $this->queryBus->query(new AuthorByEmail(new Email($email)));
        $this->assertNotNull($author);

        if (null !== $name) {
            $this->assertEquals($name, (string) $author->getName());
        }

        if (null !== $surname) {
            $this->assertEquals($surname, (string) $author->getSurname());
        }

        return $author;
    }

    public function canSeeResetPasswordTokenGenerated(Author $author):void
    {
        $this->assertNotNull(
            $this->queryBus->query(new TokenByAuthor($author)),
            sprintf(
                'No password reset token for author "%s"',
                (string) $author->getEmail()
            )
        );
    }

    private function getTokenStorage(): TokenStorageInterface
    {
        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->symfony->grabService('security.token_storage');
        return $tokenStorage;
    }

    private function getSession(): SessionInterface
    {
        /** @var SessionInterface $session */
        $session = $this->symfony->grabService('session');
        return $session;
    }
}
