<?php

namespace AppBundle\Pagination\Chapter;

use AppBundle\Entity\Book;
use AppBundle\Entity\Repository\ChapterRepository;
use AppBundle\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;

class ForBookPaginator extends Paginator
{
    /**
     * @var ChapterRepository
     */
    private $repository;

    /**
     * @var Book
     */
    private $book;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getForBookResults(Book $book, int $page = 1, int $maxPerPage = 10)
    {
        $this->book = $book;

        return $this->getResults($page, $maxPerPage);
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->repository->createForBookQb($this->book);
    }
}
