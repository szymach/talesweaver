<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Scene;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Scenes;

final class PublicationsPageHandler implements QueryHandlerInterface
{
    /**
     * @var Scenes
     */
    private $scenes;

    public function __construct(Scenes $scenes)
    {
        $this->scenes = $scenes;
    }

    public function __invoke(PublicationsPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter(
                $this->scenes->createPublicationListPage($query->getScene())
            )
        );
        $pager->setMaxPerPage(9);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
