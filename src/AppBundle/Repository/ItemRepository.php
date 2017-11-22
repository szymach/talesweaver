<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Repository\Doctrine\ItemRepository as DoctrineRepository;
use AppBundle\Repository\Traits\SceneItemRepositoryTrait;
use AppBundle\Security\UserProvider;

class ItemRepository
{
    use SceneItemRepositoryTrait;

    public function __construct(
        DoctrineRepository $doctrineRepository,
        UserProvider $userProvider
    ) {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
    }
}
