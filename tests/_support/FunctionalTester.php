<?php

use _generated\FunctionalTesterActions;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use Codeception\Actor;
use Codeception\Lib\Friend;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends Actor
{
    use FunctionalTesterActions;

    const USER_EMAIL = 'test@example.com';

    public function loginAsUser()
    {
        /* @var $tokenStorage TokenStorageInterface */
        $tokenStorage = $this->grabService('security.token_storage');
        $firewall = 'main';
        $user = $this->getUser();
        $token = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $firewall,
            $user->getRoles()
        );
        $tokenStorage->setToken($token);
         /** @var Session $session */
        $session = $this->grabService('session');
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->setCookie($session->getName(), $session->getId());
    }

    public function getUser(): User
    {
        $user = $this->grabEntityFromRepository(User::class, ['username' => self::USER_EMAIL]);
        if (!$user) {
            $role = new UserRole('ROLE_USER');
            $user = new User('test@example.com', 'password', [$role]);
            $this->persistEntity($role);
            $this->persistEntity($user);
            $this->flushToDatabase();
        }

        return $user;
    }
}
