<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use App\Repository\Doctrine\LocationRepository as DoctrineRepository;
use App\Repository\Traits\SceneItemRepositoryTrait;
use App\Security\UserProvider;

/**
 * @property DoctrineRepository $doctrineRepository
 * @property UserProvider $userProvider
 */
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

    public function find(string $id): ?Location
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUser()
        ]);
    }
}
