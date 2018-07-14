<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Security;

use Talesweaver\Domain\Author;

interface AuthorAccessInterface
{
    public function isAllowed(Author $author): bool;
}
