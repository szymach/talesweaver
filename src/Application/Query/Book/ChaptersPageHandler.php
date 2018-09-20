<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Book\ChapterPaginator;

class ChaptersPageHandler implements QueryHandlerInterface
{
    /**
     * @var ChapterPaginator
     */
    private $pagination;

    public function __construct(ChapterPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(ChaptersPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getBook(), $query->getPage(), 3);
    }
}
