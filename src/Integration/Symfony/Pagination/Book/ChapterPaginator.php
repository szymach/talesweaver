<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Pagination\Book;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Domain\Book;
use Talesweaver\Integration\Symfony\Repository\ChapterRepository;

class ChapterPaginator
{
    /**
     * @var ChapterRepository
     */
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(Book $book, int $page, int $maxPerPage = 3): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->repository->findForBook($book)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
