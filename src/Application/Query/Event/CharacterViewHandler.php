<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Event;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Events;

final class CharacterViewHandler implements QueryHandlerInterface
{
    /**
     * @var Events
     */
    private $events;

    public function __construct(Events $events)
    {
        $this->events = $events;
    }

    public function __invoke(CharacterView $query): array
    {
        return $this->events->findForCharacter($query->getCharacter());
    }
}
