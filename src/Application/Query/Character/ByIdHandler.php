<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Characters;

final class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Characters
     */
    private $characters;

    public function __construct(Characters $characters)
    {
        $this->characters = $characters;
    }

    public function __invoke(ById $query): ?Character
    {
        return $this->characters->find($query->getId());
    }
}
