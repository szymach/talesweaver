<?php

declare(strict_types=1);

namespace Talesweaver\Application\Event;

use Talesweaver\Domain\Author;

final class AuthorRegistered
{
    /**
     * @var Author
     */
    private $author;

    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
