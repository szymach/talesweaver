<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository;

use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Doctrine\Repository\CharacterRepository as DoctrineRepository;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Characters;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Repository\Provider\UserProvider;

class CharacterRepository implements Characters, RequestSecuredRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(DoctrineRepository $doctrineRepository, UserProvider $userProvider)
    {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
    }

    public function getClassName(): string
    {
        return $this->doctrineRepository->getClassName();
    }

    public function find(UuidInterface $id): ?Character
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUser()->getAuthor()
        ]);
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findAll();
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.id = :id')
            ->getQuery()
            ->execute(['id' => (string) $id])
        ;
    }

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
