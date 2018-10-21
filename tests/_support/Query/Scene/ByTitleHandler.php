<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Query\Scene;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;

class ByTitleHandler implements QueryHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $books)
    {
        $this->scenes = $books;
    }

    public function __invoke(ByTitle $query): ?Scene
    {
        return $this->scenes->findOneByTitle($query->getTitle());
    }
}
