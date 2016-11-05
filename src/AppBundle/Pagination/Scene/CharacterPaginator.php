<?php

namespace AppBundle\Pagination\Scene;

use AppBundle\Entity\Repository\CharacterRepository;

/**
 * @property CharacterRepository $repository
 */
class CharacterPaginator extends ForScenePaginator
{
    public function __construct(CharacterRepository $repository)
    {
        $this->repository = $repository;
    }
}
