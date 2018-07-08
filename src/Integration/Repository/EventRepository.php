<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository;

use Talesweaver\Integration\Repository\Interfaces\FindableByIdRepository;
use Talesweaver\Integration\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Talesweaver\Doctrine\Repository\EventRepository as DoctrineRepository;
use Talesweaver\Domain\Scene;
use Ramsey\Uuid\UuidInterface;

class EventRepository implements FindableByIdRepository
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

    public function find(string $id)
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUser()
        ]);
    }

    public function createForSceneQueryBuilder(Scene $scene): QueryBuilder
    {
        return $this->doctrineRepository->createForSceneQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scene
        );
    }

    public function findInEventsById(UuidInterface $id): array
    {
        return $this->doctrineRepository->findInEventsById(
            $this->userProvider->fetchCurrentUser(),
            $id
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
