<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Scene;
use AppBundle\Repository\Doctrine\EventRepository as DoctrineRepository;
use AppBundle\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class EventRepository
{
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

    public function createForSceneQueryBuilder(Scene $scene) : QueryBuilder
    {
        return $this->doctrineRepository->createForSceneQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $scene
        );
    }

    public function findInEventsById(UuidInterface $id) : array
    {
        return $this->doctrineRepository->findInEventsById(
            $this->userProvider->fetchCurrentUser(),
            $id
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
