<?php

namespace AppBundle\Pagination\Aggregate;

use AppBundle\Entity\Book;
use AppBundle\Pagination\Book\StandalonePaginator;
use AppBundle\Pagination\Book\ChapterPaginator;
use Pagerfanta\Pagerfanta;

class BookAggregate
{
    /**
     * @var StandalonePaginator
     */
    private $standalonePaginator;

    /**
     * @var ChapterPaginator
     */
    private $chapterPaginator;

    public function __construct(
        StandalonePaginator $standalonePaginator,
        ChapterPaginator $chapterPaginator
    ) {
        $this->standalonePaginator = $standalonePaginator;
        $this->chapterPaginator = $chapterPaginator;
    }

    /**
     * @return Pagerfanta
     */
    public function getStandalone($page)
    {
        return $this->standalonePaginator->getResults($page);
    }

    /**
     * @param Book $book
     * @return Pagerfanta
     */
    public function getChaptersForBook(Book $book, $page)
    {
        return $this->chapterPaginator->getForBookResults($book, $page);
    }
}
