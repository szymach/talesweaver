<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security;

use Talesweaver\Domain\Author;

interface AuthorContext
{
    public function getAuthor(): ?Author;
}
