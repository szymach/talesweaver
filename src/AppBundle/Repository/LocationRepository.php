<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Doctrine\LocationRepository as DoctrineRepository;
use AppBundle\Repository\Traits\SceneItemRepositoryTrait;
use AppBundle\Security\UserProvider;

class LocationRepository
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
