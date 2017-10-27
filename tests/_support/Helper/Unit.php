<?php

namespace Helper;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Security\CodeGenerator\ActivationCodeGenerator;
use Codeception\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 *  Here you can define custom actions.
 *  All public methods declared in helper class will be available in $I.
 */
class Unit extends Module
{
    const LOCALE = 'pl';
    const USER_EMAIL = 'test@example.com';

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
        return $request;
    }

    public function getUser(): User
    {
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => self::USER_EMAIL]);
        if (!$user) {
            $manager = $this->getEntityManager();
            $role = new UserRole('ROLE_USER');
            $user = new User(self::USER_EMAIL, 'password', [$role], new ActivationCodeGenerator());
            $user->activate();
            $manager->persist($role);
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

         /** @var Session $session */
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

    public function _beforeSuite($settings = [])
    {
        $this->clearUsers();
    }

    public function _afterSuite($settings = [])
    {
        $this->clearUsers();
    }

    private function clearUsers(): void
    {
        $users = $this->getEntityManager()->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $this->getEntityManager()->remove($user);
        }

        $roles = $this->getEntityManager()->getRepository(UserRole::class)->findAll();
        foreach ($roles as $role) {
            $this->getEntityManager()->remove($role);
        }

        if (count($users) || count($roles)) {
            $this->getEntityManager()->flush();
        }
    }

    private function getService(string $name)
    {
        return $this->getModule('Symfony')->grabService($name);
    }
}
