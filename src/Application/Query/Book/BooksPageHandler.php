<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Data\Cell;
use Talesweaver\Application\Data\DataSet;
use Talesweaver\Application\Data\Header;
use Talesweaver\Application\Data\Row;
use Talesweaver\Domain\Books;

final class BooksPageHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(BooksPage $query): DataSet
    {
        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($this->mapListToRows())
        );
        $pagerfanta->setMaxPerPage($query->getPerPage());
        $pagerfanta->setCurrentPage($query->getPage());

        return new DataSet(
            [new Header('book.title', true)],
            $pagerfanta
        );
    }

    private function mapListToRows(): array
    {
        return array_map(
            function (array $row): Row {
                return new Row(
                    Uuid::fromString($row['id']),
                    [new Cell($row['title'])]
                );
            },
            $this->books->createListView()
        );
    }
}
