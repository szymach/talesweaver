<?php

declare(strict_types=1);

namespace App\Repository\Traits;

use App\Security\UserProvider;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Domain\Entity\Scene;
use Ramsey\Uuid\UuidInterface;

trait SceneItemRepositoryTrait
{
    /**
     * @var EntityRepository
     */
    private $doctrineRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function createForSceneQueryBuilder(Scene $scene): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserForSceneQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scene
        );
    }

    public function createRelatedQueryBuilder(Scene $scene): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserRelatedQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scene
        );
    }

    public function createRelatedToScenesQueryBuilder(array $scenes): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserRelatedToScenesQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scenes
        );
    }

    public function entityExists(array $parameters, ?UuidInterface $id): bool
    {
        return $this->doctrineRepository->entityExists(
            $this->userProvider->fetchCurrentUser(),
            $parameters,
            $id
        );
    }
}
