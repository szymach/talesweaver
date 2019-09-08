<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Security\ActivateAdministrator;
use Talesweaver\Application\Command\Security\AddAdministrator;
use Talesweaver\Application\Query\Security\AdministratorByEmail;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Symfony\Security\AdministratorUser;

final class AdministrationModule extends Module
{
    public const ADMINISTRATOR_EMAIL = 'admin@example.com';
    public const ADMINISTRATOR_PASSWORD = 'password123';
    public const ADMINISTRATOR_ROLE = 'ROLE_ADMIN';
    private const FIREWALL = 'admin';

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

    public function loginAsAnAdministrator(
        string $email = self::ADMINISTRATOR_EMAIL,
        string $password = self::ADMINISTRATOR_PASSWORD,
        bool $active = true
    ): void {
        $user = new AdministratorUser($this->grabAdministrator($email, $password, $active));
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

    public function grabAdministrator(
        string $email = self::ADMINISTRATOR_EMAIL,
        string $password = self::ADMINISTRATOR_PASSWORD,
        bool $active = true
    ): Administrator {
        $emailVO = new Email($email);
        $administrator = $this->queryBus->query(new AdministratorByEmail($emailVO));
        if (null === $administrator) {
            $this->commandBus->dispatch(
                new AddAdministrator(
                    new Administrator(Uuid::uuid4(), new Email($email), $password)
                )
            );

            $administrator = $this->queryBus->query(new AdministratorByEmail($emailVO));
            if (true === $active) {
                $this->commandBus->dispatch(new ActivateAdministrator($administrator));
            }
        }

        return $administrator;
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
