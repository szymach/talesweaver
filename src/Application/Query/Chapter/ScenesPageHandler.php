<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Chapter;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ScenePaginator;

class ScenesPageHandler implements QueryHandlerInterface
{
    /**
     * @var ScenePaginator
     */
    private $pagination;

    public function __construct(ScenePaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(ScenesPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getChapter(), $query->getPage(), 3);
    }
}
