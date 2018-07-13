<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Helper;

use Codeception\Module;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Integration\Doctrine\Entity\User;
use function generate_user_token;

/**
 *  Here you can define custom actions.
 *  All public methods declared in helper class will be available in $I.
 */
class Unit extends Module
{
    public const LOCALE = 'pl';
    public const USER_EMAIL = 'test@example.com';
    public const USER_ROLE = 'ROLE_USER';

    public function createForm($class, $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(
            $class,
            $data,
            array_merge($options, ['csrf_protection' => false])
        );
    }

    public function getRequest(array $postData): Request
    {
        $request = new Request([], $postData);
        $request->setMethod(Request::METHOD_POST);
        $request->setLocale(self::LOCALE);
        $request->setDefaultLocale(self::LOCALE);
        return $request;
    }

    public function getUser(): User
    {
        $manager = $this->getEntityManager();
        $user = $manager->getRepository(User::class)->findOneBy(['username' => self::USER_EMAIL]);
        if (null === $user) {
            $user = new User(new Author(self::USER_EMAIL), 'password', generate_user_token());
            $user->activate();
            $manager->persist($user);
            $manager->flush();
        }

        return $user;
    }

    public function loginAsUser(): void
    {
        $firewall = 'main';
        $user = $this->getUser();
        $token = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $firewall,
            $user->getRoles()
        );

        /* @var $tokenStorage TokenStorageInterface */
        $tokenStorage = $this->getService('security.token_storage');
        $tokenStorage->setToken($token);

        /* @var $session Session */
        $session = $this->getService('session');
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->getModule('Symfony')->setCookie($session->getName(), $session->getId());
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->getModule('Doctrine2')->_getEntityManager();
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getService('form.factory');
    }

    /**
     * phpcs:disable
     */
    public function _afterSuite()
    {
        $this->clearUsers();
    }

    /**
     * phpcs:disable
     */
    public function _beforeSuite($settings = [])
    {
        $this->clearUsers();
        $this->getTranslatableListener()->setLocale(self::LOCALE);
    }

    private function getTranslatableListener(): TranslatableListener
    {
        return $this->getService('test.fsi_doctrine_extensions.listener.translatable');
    }

    private function clearUsers(): void
    {
        $manager = $this->getEntityManager();
        $users = $manager->getRepository(User::class)->findAll();
        if (0 === count($users)) {
            return;
        }

        array_walk($users, function (User $user) use ($manager): void {
            $manager->remove($user);
        });

        $this->getEntityManager()->flush();
    }

    private function getService(string $name)
    {
        return $this->getModule('Symfony')->grabService($name);
    }
}
