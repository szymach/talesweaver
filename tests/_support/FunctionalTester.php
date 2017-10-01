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

    /**
     * @var User
     */
    private $user;

    public function loginAsUser()
    {
        /* @var $tokenStorage TokenStorageInterface */
        $tokenStorage = $this->grabService('security.token_storage');
        if ($tokenStorage->getToken()) {
            return;
        }

        $firewall = 'main';
        $user = $this->getUser();
        $token = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $firewall,
            $user->getRoles()
        );
         /** @var Session $session */
        $session = $this->grabService('session');
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->setCookie($session->getName(), $session->getId());
    }

    public function getUser(): User
    {
        if (!$this->user) {
            $role = new UserRole('ROLE_USER');
            $this->user = new User('test@example.com', 'password', [$role]);
            $this->persistEntity($role);
            $this->persistEntity($this->user);
            $this->flushToDatabase();
        }

        return $this->user;
    }
}
