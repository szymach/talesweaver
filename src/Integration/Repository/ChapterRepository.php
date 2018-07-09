<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Repository;

use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Doctrine\Repository\ChapterRepository as DoctrineRepository;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Integration\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Security\UserProvider;

class ChapterRepository implements Chapters, LatestChangesAwareRepository, RequestSecuredRepository
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

    public function find(UuidInterface $id): ?Chapter
    {
        return $this->doctrineRepository->findOneBy([
            'id' => (string) $id,
            'createdBy' => $this->userProvider->fetchCurrentUser()
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
