<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Scene;
use App\Repository\Doctrine\EventRepository as DoctrineRepository;
use App\Repository\Interfaces\FindableByIdRepository;
use App\Repository\Traits\ParamConverterRepository;
use App\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class EventRepository implements FindableByIdRepository
{
    use ParamConverterRepository;

    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(
        DoctrineRepository $doctrineRepository,
        UserProvider $userProvider
    ) {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
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
