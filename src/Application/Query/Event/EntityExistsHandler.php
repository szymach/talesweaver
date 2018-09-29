<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Event;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Events;

class EntityExistsHandler implements QueryHandlerInterface
{
    /**
     * @var Events
     */
    private $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function __invoke(EntityExists $query): bool
    {
        return $this->events->entityExists($query->getName(), $query->getId(), $query->getSceneId());
    }
}
