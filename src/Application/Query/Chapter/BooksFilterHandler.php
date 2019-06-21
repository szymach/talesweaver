<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Books;

final class BooksFilterHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function __invoke(BooksFilter $query): array
    {
        return [
            'book' => [
                'options' => $this->books->createListView(),
                'selected' => $query->getSelectedId()
            ]
        ];
    }
}
