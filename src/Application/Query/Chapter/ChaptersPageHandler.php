<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Data\Cell;
use Talesweaver\Application\Data\DataSet;
use Talesweaver\Application\Data\Header;
use Talesweaver\Application\Data\Row;
use Talesweaver\Application\Data\Sortable;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapters;

final class ChaptersPageHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    /**
     * @var Sortable
     */
    private $sortable;

    public function __construct(Chapters $chapters, Sortable $sortable)
    {
        $this->chapters = $chapters;
        $this->sortable = $sortable;
    }

    public function __invoke(ChaptersPage $query): DataSet
    {
        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($this->mapListToRows($query->getBook()))
        );
        $pagerfanta->setMaxPerPage($query->getPerPage());
        $pagerfanta->setCurrentPage($query->getPage());

        return new DataSet(
            [
                new Header('chapter.title', 'title', true),
                new Header('chapter.book', 'book', true)
            ],
            $pagerfanta
        );
    }

    private function mapListToRows(?Book $book): array
    {
        return array_map(
            function (array $row): Row {
                return new Row(
                    Uuid::fromString($row['id']),
                    [
                        new Cell($row['title']),
                        new Cell($row['book'])
                    ]
                );
            },
            $this->chapters->createListView($book, $this->sortable->createFromSession('chapters'))
        );
    }
}
