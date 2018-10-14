<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Module;

use Codeception\Module;
use Codeception\Module\Symfony;
use Codeception\TestInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Authors;
use Talesweaver\Domain\PasswordResetToken;
use Talesweaver\Domain\PasswordResetTokens;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Integration\Symfony\Security\User;
use function generate_user_token;

class AuthorModule extends Module
{
    public const AUTHOR_EMAIL = 'test@example.com';
    public const AUTHOR_PASSWORD = 'password123';
    public const AUTHOR_ROLE = 'ROLE_USER';
    public const LOCALE = 'pl';

    /**
     * @var Symfony
     */
    private $symfony;

    public function loginAsUser(bool $active = true): void
    {
        $firewall = 'main';
        $user = new User($this->getAuthor($active));
        $token = new UsernamePasswordToken(
            $user,
            self::AUTHOR_PASSWORD,
            $firewall,
            $user->getRoles()
        );
        $this->getTokenStorage()->setToken($token);

        $session = $this->getSession();
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();

        $this->symfony->setCookie($session->getName(), $session->getId());
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
            $this->getEntityManager()->persist($author);
            $this->getEntityManager()->flush();
        }

        return $author;
    }

    public function canSeeResetPasswordTokenGenerated(Author $author):void
    {
        $token = $this->getPasswordResetTokenRepository()->findOneByAuthor($author);
        $this->assertNotNull($token, sprintf(
            'No password reset token for author "%s"',
            (string) $author->getEmail()
        ));
    }

    public function _before(TestInterface $test)
    {
        $this->symfony = $this->getModule('Symfony');
        $this->clearAuthors();
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->symfony->grabService('doctrine.orm.entity_manager');
    }

    private function getAuthorRepository(): Authors
    {
        return $this->getEntityManager()->getRepository(Author::class);
    }

    private function getPasswordResetTokenRepository(): PasswordResetTokens
    {
        return $this->getEntityManager()->getRepository(PasswordResetToken::class);
    }

    private function getTokenStorage(): TokenStorageInterface
    {
        return $this->symfony->grabService('security.token_storage');
    }

    private function getSession(): SessionInterface
    {
        return $this->symfony->grabService('session');
    }

    private function clearAuthors(): void
    {
        $author = $this->getAuthorRepository()->findOneByEmail(new Email(self::AUTHOR_EMAIL));
        if (null === $author) {
            return;
        }

        $manager = $this->getEntityManager();
        $manager->remove($author);
        $manager->flush();
    }
}
