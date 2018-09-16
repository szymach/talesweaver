<?php

declare(strict_types=1);

namespace Talesweaver\Tests;

use Codeception\Actor;
use Codeception\Lib\Friend;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;
use Talesweaver\Domain\PasswordResetToken;
use Talesweaver\Domain\PasswordResetTokens;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Symfony\Security\User;
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
    public const AUTHOR_EMAIL = 'test@example.com';
    public const AUTHOR_PASSWORD = 'password123';
    public const AUTHOR_ROLE = 'ROLE_USER';
    public const ERROR_SELECTOR = '.help-block .list-unstyled li';

    public function loginAsUser(bool $active = true): void
    {
        $firewall = 'main';
        $author = new User($this->getAuthor($active));
        $token = new UsernamePasswordToken(
            $author,
            self::AUTHOR_PASSWORD,
            $firewall,
            $author->getRoles()
        );
        $this->getTokenStorage()->setToken($token);

        $session = $this->getSession();
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->setCookie($session->getName(), $session->getId());
    }

    public function getAuthor(bool $active = true, string $email = self::AUTHOR_EMAIL): Author
    {
        $emailVO = new Email($email);
        $author = $this->getAuthorRepository()->findOneByEmail($emailVO);
        if (null === $author) {
            $author = new Author(Uuid::uuid4(), $emailVO, self::AUTHOR_PASSWORD, generate_user_token());
            if (true === $active) {
                $author->activate();
            }
            $this->persistEntity($author);
            $this->flushToDatabase();
        }

        return $author;
    }

    public function createTooLongString(): string
    {
        return bin2hex(random_bytes(128));
    }

    public function createForm(string $class, $data = null, array $options = []): FormInterface
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

    public function canSeeResetPasswordTokenGenerated(Author $author):void
    {
        $token = $this->getPasswordResetTokenRepository()->findOneByAuthor($author);
        $this->assertNotNull($token, sprintf(
            'No password reset token for author "%s"',
            (string) $author->getEmail()
        ));
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

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->grabService('doctrine.orm.entity_manager');
    }

    /**
     * phpcs:disable
     */
    public function _afterSuite()
    {
        $this->clearAuthors();
    }

    /**
     * phpcs:disable
     */
    public function _beforeSuite($settings = [])
    {
        $this->clearAuthors();
        $this->getTranslatableListener()->setLocale(self::LOCALE);
    }

    private function getAuthorRepository(): Authors
    {
        return $this->getEntityManager()->getRepository(Author::class);
    }

    private function getPasswordResetTokenRepository(): PasswordResetTokens
    {
        return $this->getEntityManager()->getRepository(PasswordResetToken::class);
    }

    private function getRouter(): RouterInterface
    {
        return $this->grabService('router');
    }

    private function getTokenStorage(): TokenStorageInterface
    {
        return $this->grabService('security.token_storage');
    }

    private function getSession(): SessionInterface
    {
        return $this->grabService('session');
    }

    private function getFormFactory(): FormFactoryInterface
    {
        return $this->grabService('form.factory');
    }

    private function getTranslatableListener(): TranslatableListener
    {
        return $this->grabService('test.fsi_doctrine_extensions.listener.translatable');
    }
}
