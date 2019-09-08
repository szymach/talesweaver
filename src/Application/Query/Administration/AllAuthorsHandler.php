<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Administration;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Application\Data\Cell;
use Talesweaver\Application\Data\DataSet;
use Talesweaver\Application\Data\Header;
use Talesweaver\Application\Data\Row;
use Talesweaver\Domain\Authors;

final class AllAuthorsHandler implements QueryHandlerInterface
{
    /**
     * @var Authors
     */
    private $authors;

    public function __construct(Authors $authors)
    {
        $this->authors = $authors;
    }

    public function __invoke(AllAuthors $query): DataSet
    {
        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($this->mapListToRows())
        );

        return new DataSet(
            [
                new Header('author.email', 'email', false),
                new Header('author.active', 'active', false)
            ],
            $pagerfanta
        );
    }

    private function mapListToRows(): array
    {
        return array_map(
            function (array $row): Row {
                return new Row(
                    Uuid::fromString($row['id']),
                    [new Cell($row['email']), new Cell($row['active'], 'bool.%s')]
                );
            },
            $this->authors->createListView()
        );
    }
}
