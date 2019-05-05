<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Characters;

final class RelatedPageHandler implements QueryHandlerInterface
{
    /**
     * @var Characters
     */
    private $characters;

    public function __construct(Characters $characters)
    {
        $this->characters = $characters;
    }

    public function __invoke(RelatedPage $query): Pagerfanta
    {
        $pager = new Pagerfanta(
            new ArrayAdapter($this->characters->findRelated($query->getScene()))
        );
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($query->getPage());

        return $pager;
    }
}
