<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Doctrine\ORM\EntityManagerInterface;
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
use Talesweaver\Integration\Symfony\Security\User;
use Talesweaver\Tests\Query\Security\TokenByAuthor;

class AuthorModule extends Module
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
        string $password = self::AUTHOR_PASSWORD
    ): void {
        $user = new User($this->getAuthor($email, $password));
        $token = new UsernamePasswordToken(
            $user,
            self::AUTHOR_PASSWORD,
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
        bool $active = true
    ): Author {
        $emailVO = new Email($email);
        $author = $this->queryBus->query(new AuthorByEmail($emailVO));
        if (null === $author) {
            $this->commandBus->dispatch(new CreateAuthor($email, $password));
            $author = $this->queryBus->query(new AuthorByEmail($emailVO));
            if (true === $active) {
                $this->commandBus->dispatch(new ActivateAuthor($author));
            }
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

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->symfony->grabService('doctrine.orm.entity_manager');
    }

    private function getTokenStorage(): TokenStorageInterface
    {
        return $this->symfony->grabService('security.token_storage');
    }

    private function getSession(): SessionInterface
    {
        return $this->symfony->grabService('session');
    }
}
