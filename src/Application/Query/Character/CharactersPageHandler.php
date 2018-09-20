<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Character;

use Pagerfanta\Pagerfanta;
use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Integration\Symfony\Pagination\Character\CharacterPaginator;

class CharactersPageHandler implements QueryHandlerInterface
{
    /**
     * @var CharacterPaginator
     */
    private $pagination;

    public function __construct(CharacterPaginator $pagination)
    {
        $this->pagination = $pagination;
    }

    public function __invoke(CharactersPage $query): Pagerfanta
    {
        return $this->pagination->getResults($query->getScene(), $query->getPage());
    }
}
