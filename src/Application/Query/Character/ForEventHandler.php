<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Characters;

final class ForEventHandler implements QueryHandlerInterface
{
    /**
     * @var Characters
     */
    private $characters;

    public function __construct(Characters $characters)
    {
        $this->characters = $characters;
    }

    public function __invoke(ForEvent $query): array
    {
        return $this->characters->findForEvent($query->getScene());
    }
}
