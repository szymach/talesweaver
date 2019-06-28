<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

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
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scenes;

final class ScenesPageHandler implements QueryHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    /**
     * @var Sortable
     */
    private $sortable;

    public function __construct(Scenes $scenes, Sortable $sortable)
    {
        $this->scenes = $scenes;
        $this->sortable = $sortable;
    }

    public function __invoke(ScenesPage $query): DataSet
    {
        $pagerfanta = new Pagerfanta(
            new ArrayAdapter(
                $this->mapListToRows($query->getBook(), $query->getChapter())
            )
        );
        $pagerfanta->setMaxPerPage($query->getPerPage());
        $pagerfanta->setCurrentPage($query->getPage());

        return new DataSet(
            [
                new Header('scene.title', 'title', true),
                new Header('scene.chapter', 'chapter', true),
                new Header('scene.book', 'book', true)
            ],
            $pagerfanta
        );
    }

    private function mapListToRows(?Book $book, ?Chapter $chapter): array
    {
        return array_map(
            function (array $row): Row {
                return new Row(
                    Uuid::fromString($row['id']),
                    [
                        new Cell($row['title']),
                        new Cell($row['chapter']),
                        new Cell($row['book'])
                    ]
                );
            },
            $this->scenes->createListView($book, $chapter, $this->sortable->createFromSession('scenes'))
        );
    }
}
