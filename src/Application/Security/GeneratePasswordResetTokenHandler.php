<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Talesweaver\Application\Mailer\AuthorActionMailer;
use Talesweaver\Domain\Authors;
use Talesweaver\Domain\PasswordResetTokens;
use Talesweaver\Domain\ValueObject\Email;
use function generate_user_token;

class GeneratePasswordResetTokenHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var PasswordResetTokens
     */
    private $tokens;

    /**
     * @var Authors
     */
    private $authors;

    /**
     * @var AuthorActionMailer
     */
    private $passwordResetMailer;

    public function __construct(
        EntityManagerInterface $manager,
        PasswordResetTokens $tokens,
        Authors $authors,
        AuthorActionMailer $passwordResetMailer
    ) {
        $this->manager = $manager;
        $this->tokens = $tokens;
        $this->authors = $authors;
        $this->passwordResetMailer = $passwordResetMailer;
    }

    public function handle(GeneratePasswordResetToken $command): void
    {
        $email = $command->getEmail();
        if (true === $this->isRequestTooSoon($email)) {
            return;
        }

        $this->tokens->deactivatePreviousTokens($email);

        $author = $this->authors->findOneByEmail($email);
        if (null === $author) {
            throw new RuntimeException(sprintf('No author found for email "%s"', $email));
        }

        $author->addPasswordResetToken(generate_user_token());
        $this->passwordResetMailer->send($author);
    }

    private function isRequestTooSoon(Email $email): bool
    {
        $previousTokenDate = $this->tokens->findCreationDateOfPrevious($email);
        if (null === $previousTokenDate) {
            return false;
        }

        /* @var $diff DateInterval */
        $diff = $previousTokenDate->diff(new DateTimeImmutable());
        if ($diff->days >= 1) {
            return false;
        }

        if ($diff->h >= 1 || $diff->i >= 5) {
            return false;
        }

        return true;
    }
}
