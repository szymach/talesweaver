<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Books;
use Talesweaver\Domain\Chapters;

final class FiltersHandler implements QueryHandlerInterface
{
    /**
     * @var Books
     */
    private $books;

    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Books $books, Chapters $chapters)
    {
        $this->books = $books;
        $this->chapters = $chapters;
    }

    public function __invoke(Filters $query): array
    {
        $book = $query->getBook();
        $chapter = $query->getChapter();
        return [
            'book' => [
                'options' => $this->books->createListView(),
                'selected' => null !== $book ? $book->getId() : null
            ],
            'chapter' => [
                'options' => $this->chapters->createListView($book),
                'selected' => null !== $chapter ? $chapter->getId() : null
            ]
        ];
    }
}
