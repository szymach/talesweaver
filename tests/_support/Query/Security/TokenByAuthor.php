<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Query\Security;

use Talesweaver\Domain\Author;

class TokenByAuthor
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
