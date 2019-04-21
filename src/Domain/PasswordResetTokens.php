<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use Talesweaver\Domain\ValueObject\Email;

interface PasswordResetTokens
{
    /**
     * Used only for testing purposes
     * @param Author $author
     * @return PasswordResetToken|null
     */
    public function findOneByAuthor(Author $author): ?PasswordResetToken;
    public function findActiveByCode(string $code): ?PasswordResetToken;
    public function findCreationDateOfPrevious(Email $email): ?DateTimeImmutable;
    public function deactivatePreviousTokens(Email $email): void;
}
