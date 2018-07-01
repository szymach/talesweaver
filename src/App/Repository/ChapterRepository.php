<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Interfaces\FindableByIdRepository;
use App\Repository\Interfaces\LatestChangesAwareRepository;
use App\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Repository\ChapterRepository as DoctrineRepository;
use Domain\Entity\Book;
use Ramsey\Uuid\UuidInterface;

class ChapterRepository implements FindableByIdRepository, LatestChangesAwareRepository
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

    public function createAllAvailableQueryBuilder(): QueryBuilder
    {
        return $this->doctrineRepository->allAvailableByUserQueryBuilder(
            $this->userProvider->fetchCurrentUser()
        );
    }

    public function createStandaloneQueryBuilder(): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserQueryBuilder(
            $this->userProvider->fetchCurrentUser()
        );
    }

    public function createForBookQb(Book $book): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserForBookQueryBuilder(
            $this->userProvider->fetchCurrentUser(),
            $book
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
