<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Doctrine\BookRepository as DoctrineRepository;
use AppBundle\Repository\Interfaces\LatestChangesAwareRepository;
use AppBundle\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class BookRepository implements LatestChangesAwareRepository
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

    public function createQueryBuilder() : QueryBuilder
    {
        return $this->doctrineRepository->createByUserQueryBuilder(
            $this->userProvider->fetchCurrentUser()
        );
    }

    public function findLatest(string $locale, string $label = 'title', int $limit = 5) : array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUser(),
            $locale,
            $label,
            $limit
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