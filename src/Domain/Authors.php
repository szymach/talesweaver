<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Talesweaver\Domain\ValueObject\Email;

interface Authors
{
    public function findOneByActivationToken(string $code): ?Author;
    public function findOneByEmail(Email $email): ?Author;
}
