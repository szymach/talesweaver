<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository;

use Talesweaver\Integration\Repository\Interfaces\FindableByIdRepository;
use Talesweaver\Integration\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Talesweaver\Doctrine\Repository\BookRepository as DoctrineRepository;
use Ramsey\Uuid\UuidInterface;

class BookRepository implements FindableByIdRepository, LatestChangesAwareRepository
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

    public function createQueryBuilder(): QueryBuilder
    {
        return $this->doctrineRepository->createByUserQueryBuilder(
            $this->userProvider->fetchCurrentUser()
        );
    }

    public function findLatest(string $locale, string $label = 'title', int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUser(),
            $locale,
            $label,
            $limit
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
