<?php

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

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

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->grabService('form.factory');
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
        $manager = $this->getEntityManager();
        $user = $manager->getRepository(User::class)->findOneBy(['username' => self::USER_EMAIL]);
        if (!$user) {
            $role = new UserRole('ROLE_USER');
            $user = new User(self::USER_EMAIL, 'password', [$role]);
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
        $tokenStorage = $this->grabService('security.token_storage');
        $tokenStorage->setToken($token);

         /** @var Session $session */
        $session = $this->grabService('session');
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->setCookie($session->getName(), $session->getId());
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->grabService('doctrine.orm.entity_manager');
    }

    public function getRouter(): RouterInterface
    {
        return $this->grabService('router');
    }
}
