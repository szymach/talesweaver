<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Event;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\EventPaginator;

class EventsPageHandler implements QueryHandlerInterface
{
    /**
     * @var EventPaginator
     */
    private $pagination;

    public function __construct(EventPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(EventsPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getScene(), $query->getPage());
    }
}
