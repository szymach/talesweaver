<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Character;
use AppBundle\Repository\Doctrine\CharacterRepository as DoctrineRepository;
use AppBundle\Repository\Traits\SceneItemRepositoryTrait;
use AppBundle\Security\UserProvider;

/**
 * @property DoctrineRepository $doctrineRepository
 * @property UserProvider $userProvider
 */
class CharacterRepository
{
    use SceneItemRepositoryTrait;

    public function __construct(
        DoctrineRepository $doctrineRepository,
        UserProvider $userProvider
    ) {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
    }

    public function find(string $id): ?Character
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUser()
        ]);
    }
}
