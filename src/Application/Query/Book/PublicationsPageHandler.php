<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Books;

final class PublicationsPageHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(PublicationsPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter(
                $this->books->createPublicationListPage($query->getBook())
            )
        );
        $pager->setMaxPerPage(9);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
