<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

final class BooksPage
{
    /**
     * @var int
     */
    private $page;

    public function __construct(int $page)
    {
        $this->page = $page;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
