<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use DateInterval;
use DateTimeImmutable;
use RuntimeException;
use Talesweaver\Application\Bus\CommandHandlerInterface;
use Talesweaver\Application\Bus\EventBus;
use Talesweaver\Domain\Authors;
use Talesweaver\Domain\PasswordResetTokens;
use Talesweaver\Domain\ValueObject\Email;
use function generate_user_token;

class GeneratePasswordResetTokenHandler implements CommandHandlerInterface
{
    /**
     * @var PasswordResetTokens
     */
    private $tokens;

    /**
     * @var Authors
     */
    private $authors;

    /**
     * @var EventBus
     */
    private $eventBus;

    public function __construct(
        PasswordResetTokens $tokens,
        Authors $authors,
        EventBus $eventBus
    ) {
        $this->tokens = $tokens;
        $this->authors = $authors;
        $this->eventBus = $eventBus;
    }

    public function __invoke(GeneratePasswordResetToken $command): void
    {
        $email = $command->getEmail();
        if (true === $this->isRequestTooSoon($email)) {
            return;
        }

        $this->tokens->deactivatePreviousTokens($email);
        $author = $this->authors->findOneByEmail($email);
        if (null === $author) {
            throw new RuntimeException("No author found for email \"{$email->getValue()}\"");
        }

        $author->addPasswordResetToken(generate_user_token());
        $this->eventBus->send($author);
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
