<?php

namespace AppBundle\Repository\Traits;

use AppBundle\Entity\Scene;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

trait SceneItemRepositoryTrait
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserForSceneQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scene
        );
    }

    public function createRelatedQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserRelatedQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scene
        );
    }

    public function createRelatedToScenesQueryBuilder(array $scenes)
    {
        return $this->doctrineRepository->byCurrentUserRelatedToScenesQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scenes
        );
    }

    public function entityExists(array $parameters, ?UuidInterface $id) : bool
    {
        return $this->doctrineRepository->entityExists(
            $this->userProvider->fetchCurrentUser(),
            $parameters,
            $id
        );
    }
}
