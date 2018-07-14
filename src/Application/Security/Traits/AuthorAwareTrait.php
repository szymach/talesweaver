<?php

declare(strict_types=1);

namespace Talesweaver\Application\Security\Traits;

use Talesweaver\Domain\Author;

trait AuthorAwareTrait
{
    /**
     * @var Author
     */
    private $author;

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
