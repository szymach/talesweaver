<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Scenes;

class EntityExistsHandler implements QueryHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $scenes)
    {
        $this->scenes = $scenes;
    }

    public function __invoke(EntityExists $query): bool
    {
        return $this->scenes->entityExists(
            $query->getTitle(),
            $query->getId(),
            $query->getChapterId()
        );
    }
}
