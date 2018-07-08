<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository;

use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Doctrine\Repository\BookRepository as DoctrineRepository;
use Talesweaver\Integration\Repository\Interfaces\FindableByIdRepository;
use Talesweaver\Integration\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Security\UserProvider;

class BookRepository implements FindableByIdRepository, LatestChangesAwareRepository, RequestSecuredRepository
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
