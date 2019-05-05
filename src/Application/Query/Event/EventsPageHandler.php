<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Event;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Events;

final class EventsPageHandler implements QueryHandlerInterface
{
    /**
     * @var Events
     */
    private $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function __invoke(EventsPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->events->findForScene($query->getScene()))
        );
        $pager->setMaxPerPage(9);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
