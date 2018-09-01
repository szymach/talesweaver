<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use DateTimeImmutable;
use Talesweaver\Domain\ValueObject\Email;

interface PasswordResetTokens
{
    public function findOneByEmail(string $email): ?PasswordResetToken;
    public function findOneByCode(string $code): ?PasswordResetToken;
    public function findOneByAuthor(Author $author): ?PasswordResetToken;
    public function findCreationDateOfPrevious(Email $email): ?DateTimeImmutable;
    public function deactivatePreviousTokens(Email $email): void;
}
