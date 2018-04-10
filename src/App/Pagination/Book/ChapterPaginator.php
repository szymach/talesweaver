<?php

declare(strict_types=1);

namespace App\Pagination\Book;

use Domain\Entity\Book;
use App\Repository\ChapterRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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

    public function getResults(Book $book, int $page, int $maxPerPage = 8): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createForBookQb($book)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
