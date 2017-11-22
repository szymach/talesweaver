<?php

declare(strict_types=1);

namespace AppBundle\Pagination\Character;

use AppBundle\Repository\CharacterRepository;
use AppBundle\Entity\Scene;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class RelatedPaginator
{
    /**
     * @var CharacterRepository
     */
    private $repository;

    public function __construct(CharacterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getResults(Scene $scene, int $page = 1, int $maxPerPage = 10): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($this->repository->createRelatedQueryBuilder($scene)));
        $pager->setMaxPerPage($maxPerPage);
        $pager->setCurrentPage($page);

        return $pager;
    }
}
