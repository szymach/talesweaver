<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Data\Filter;
use Talesweaver\Application\Data\FilterSet;
use Talesweaver\Domain\Books;

final class FiltersHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(Filters $query): FilterSet
    {
        $book = $query->getBook();
        return new FilterSet([
            new Filter(
                'book',
                $this->books->createListView(),
                null !== $book ? $book->getId() : null
            )
        ]);
    }
}
