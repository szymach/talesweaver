<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Characters;

final class ForSceneHandler implements QueryHandlerInterface
{
    /**
     * @var Characters
     */
    private $characters;

    public function __construct(Characters $characters)
    {
        $this->characters = $characters;
    }

    public function __invoke(ForScene $query): array
    {
        return $this->characters->findForScene($query->getScene());
    }
}
