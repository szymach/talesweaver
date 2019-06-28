<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Data\Filter;
use Talesweaver\Application\Data\FilterSet;
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

    public function __invoke(Filters $query): FilterSet
    {
        $book = $query->getBook();
        $chapter = $query->getChapter();
        return new FilterSet([
            new Filter(
                'book',
                $this->books->createListView(null),
                null !== $book ? $book->getId() : null
            ),
            new Filter(
                'chapter',
                $this->chapters->createListView($book, null),
                null !== $chapter ? $chapter->getId() : null
            )
        ]);
    }
}
