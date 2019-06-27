<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

final class BooksPage
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $perPage;

    public function __construct(int $page, int $perPage = 5)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
