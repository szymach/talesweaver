<?php

use _generated\FunctionalTesterActions;
use AppBundle\Entity\User;
use AppBundle\Entity\User\PasswordResetToken;
use AppBundle\Entity\UserRole;
use AppBundle\Security\TokenGenerator;
use Codeception\Actor;
use Codeception\Lib\Friend;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
 * @method Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends Actor
{
    use FunctionalTesterActions;

    public const LOCALE = 'pl';

    public const USER_EMAIL = 'test@example.com';
    public const USER_PASSWORD = 'password123';

    public const ERROR_SELECTOR = '.help-block .list-unstyled li';

    public function loginAsUser(bool $active = true): void
    {
        /* @var $tokenStorage TokenStorageInterface */
        $tokenStorage = $this->grabService('security.token_storage');
        $firewall = 'main';
        $user = $this->getUser($active);
        $token = new UsernamePasswordToken(
            $user,
            self::USER_PASSWORD,
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

    public function getUser(bool $active = true): User
    {
        $manager = $this->getEntityManager();
        $user = $manager->getRepository(User::class)->findOneBy([
            'username' => self::USER_EMAIL
        ]);
        if (!$user) {
            $role = new UserRole('ROLE_USER');
            $user = new User(
                self::USER_EMAIL,
                password_hash(self::USER_PASSWORD, PASSWORD_BCRYPT),
                [$role],
                new TokenGenerator()
            );
            if ($active) {
                $user->activate();
            }
            $this->persistEntity($role);
            $this->persistEntity($user);
            $this->flushToDatabase();
        }

        return $user;
    }

    public function seeNumberOfErrors(
        int $count,
        string $selector = self::ERROR_SELECTOR
    ): void {
        $this->seeNumberOfElements($selector, $count);
    }

    public function seeError(string $content, string $field): void
    {
        $this->see(
            $content,
            sprintf('input[name="%s"] + %s', $field, self::ERROR_SELECTOR)
        );
    }

    public function seeErrorAlert(string $content)
    {
        $this->see($content, '.alert-danger.alert-form');
    }

    public function canSeeResetPasswordTokenGenerated(User $user):void
    {
        $token = $this->getEntityManager()
            ->getRepository(PasswordResetToken::class)
            ->findOneBy(['user' => $user])
        ;
        if (!$token) {
            throw new RuntimeException(sprintf(
                'No password reset token for user "%s"',
                $user->getUsername()
            ));
        }
    }

    public function canSeeIAmOnRouteLocale(
        string $name,
        array $parameters = [],
        string $locale = self::LOCALE
    ): void {
        $url = $this->createUrl($name, $parameters, $locale);
        $this->amOnPage($url);
        $this->canSeeCurrentUrlEquals($url);
        $this->canSeeResponseCodeIs(200);
    }

    public function createUrl(
        string $name,
        array $parameters = [],
        string $locale = self::LOCALE
    ): string {
        return $this->getRouter()->generate(
            $name,
            array_merge(['_locale' => $locale], $parameters)
        );
    }

    public function canSeeAlert(string $content, string $type = 'success'): void
    {
        $this->canSee($content, sprintf('.alert.alert-%s', $type));
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
