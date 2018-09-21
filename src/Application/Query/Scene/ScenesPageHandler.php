<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Scenes;

class ScenesPageHandler implements QueryHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $scenes)
    {
        $this->scenes = $scenes;
    }

    public function __invoke(ScenesPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(new ArrayAdapter($this->scenes->findStandalone()));
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
