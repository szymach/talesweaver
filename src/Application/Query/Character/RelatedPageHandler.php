<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Character\RelatedPaginator;

class RelatedPageHandler implements QueryHandlerInterface
{
    /**
     * @var RelatedPaginator
     */
    private $pagination;

    public function __construct(RelatedPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(RelatedPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getScene(), $query->getPage());
    }
}
