<?php

declare(strict_types=1);

namespace Talesweaver\Application\Mailer;

use Talesweaver\Domain\Author;

interface AuthorActionMailer
{
    public function send(Author $author): bool;
}
