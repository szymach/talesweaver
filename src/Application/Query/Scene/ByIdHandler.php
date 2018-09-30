<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;

class ByIdHandler implements QueryHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $scenes)
    {
        $this->scenes = $scenes;
    }

    public function __invoke(ById $query): ?Scene
    {
        return $this->scenes->find($query->getId());
    }
}
