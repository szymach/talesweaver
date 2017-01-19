<?php

namespace AppBundle\Pagination\Book;

use AppBundle\Entity\Book;
use AppBundle\Pagination\Chapter\ChapterPaginator;
use Pagerfanta\Pagerfanta;

class BookAggregate
{
    /**
     * @var BookPaginator
     */
    private $bookPaginator;

    /**
     * @var ChapterPaginator
     */
    private $chapterPaginator;

    public function __construct(
        BookPaginator $bookPaginator,
        ChapterPaginator $chapterPaginator
    ) {
        $this->bookPaginator = $bookPaginator;
        $this->chapterPaginator = $chapterPaginator;
    }

    /**
     * @param $page
     * @return Pagerfanta
     */
    public function getStandalone(int $page)
    {
        return $this->bookPaginator->getStandalone($page);
    }

    /**
     * @param Book $book
     * @param $page
     * @return Pagerfanta
     */
    public function getChaptersForBook(Book $book, int $page)
    {
        return $this->chapterPaginator->getForBook($book, $page);
    }
}
