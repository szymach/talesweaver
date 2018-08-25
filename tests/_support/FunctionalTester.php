<?php

declare(strict_types=1);

namespace Talesweaver\Tests;

use Codeception\Actor;
use Codeception\Lib\Friend;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Doctrine\Entity\PasswordResetToken;
use Talesweaver\Integration\Doctrine\Entity\User;
use Talesweaver\Integration\Doctrine\Repository\UserRepository;
use Talesweaver\Tests\_generated\FunctionalTesterActions;
use function generate_user_token;

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
    public const USER_ROLE = 'ROLE_USER';
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
         /* @var $session Session */
        $session = $this->grabService('session');
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->setCookie($session->getName(), $session->getId());
    }

    public function getUser(bool $active = true, string $email = self::USER_EMAIL): User
    {
        $emailVO = new Email($email);
        $user = $this->getUserRepository()->findOneByEmail($emailVO);
        if (null === $user) {
            $user = new User(
                new Author(Uuid::uuid4(), $emailVO),
                password_hash(self::USER_PASSWORD, PASSWORD_BCRYPT),
                generate_user_token()
            );
            if (true === $active) {
                $user->activate();
            }
            $this->persistEntity($user);
            $this->flushToDatabase();
        }

        return $user;
    }

    public function createTooLongString(): string
    {
        return bin2hex(random_bytes(128));
    }

    public function createForm($class, $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create($class, $data, array_merge(
            ['csrf_protection' => false],
            $options
        ));
    }

    public function getRequest(array $postData): Request
    {
        $request = new Request([], $postData);
        $request->setMethod(Request::METHOD_POST);
        $request->setLocale(self::LOCALE);
        $request->setDefaultLocale(self::LOCALE);
        return $request;
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->grabService('form.factory');
    }

    public function seeNumberOfErrors(int $count, string $selector = self::ERROR_SELECTOR): void
    {
        $this->seeNumberOfElements($selector, $count);
    }

    public function seeError(string $content, string $field): void
    {
        $this->see($content, sprintf(
            'input[name="%s"] + %s, select[name="%s"] + %s',
            $field,
            self::ERROR_SELECTOR,
            $field,
            self::ERROR_SELECTOR
        ));
    }

    public function seeErrorAlert(string $content): void
    {
        $this->see($content, '.alert-danger.alert-form');
    }

    public function canSeeResetPasswordTokenGenerated(User $user):void
    {
        $token = $this->getEntityManager()->getRepository(PasswordResetToken::class)->findOneBy(['user' => $user]);
        if (null === $token) {
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

    public function createUrl(string $name, array $parameters = [], string $locale = self::LOCALE): string
    {
        return $this->getRouter()->generate(
            $name,
            array_merge(['_locale' => $locale], $parameters)
        );
    }

    public function canSeeAlert(string $content, string $type = 'success'): void
    {
        $this->canSee($content, sprintf('.alert.alert-%s', $type));
    }

    public function getUserRepository(): UserRepository
    {
        return $this->getEntityManager()->getRepository(User::class);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->grabService('doctrine.orm.entity_manager');
    }

    public function getRouter(): RouterInterface
    {
        return $this->grabService('router');
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

    public function findElement(string $selector)
    {
        return $this;
    }

    private function getTranslatableListener(): TranslatableListener
    {
        return $this->grabService('test.fsi_doctrine_extensions.listener.translatable');
    }
}
