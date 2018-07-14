<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Security;

use Talesweaver\Domain\Author;

interface AuthorAwareInterface
{
    public function setAuthor(Author $author): void;
    public function getAuthor(): Author;
}
