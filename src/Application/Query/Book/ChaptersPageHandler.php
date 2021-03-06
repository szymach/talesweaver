<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Book;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapters;

final class ChaptersPageHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(ChaptersPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter(
                $this->chapters->createListView($query->getBook(), null)
            )
        );
        $pager->setMaxPerPage(18);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
