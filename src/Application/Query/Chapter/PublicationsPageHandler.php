<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Chapters;

final class PublicationsPageHandler implements QueryHandlerInterface
{
    /**
     * @var Chapters
     */
    private $chapters;

    public function __construct(Chapters $chapters)
    {
        $this->chapters = $chapters;
    }

    public function __invoke(PublicationsPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter(
                $this->chapters->createPublicationListPage($query->getChapter())
            )
        );
        $pager->setMaxPerPage(9);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
