<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
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

    public function __invoke(Filters $query): array
    {
        $book = $query->getBook();
        return [
            'book' => [
                'options' => $this->books->createListView(),
                'selected' => null !== $book ? $book->getId() : null
            ]
        ];
    }
}
