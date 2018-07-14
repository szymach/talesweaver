<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\DoctrineRepository\BookRepository as DoctrineRepository;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;
use Talesweaver\Integration\Symfony\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Symfony\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Symfony\Repository\Provider\UserProvider;

class BookRepository implements Books, LatestChangesAwareRepository, RequestSecuredRepository
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

    public function find(UuidInterface $id): ?Book
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id->toString(),
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findAll();
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository
            ->createQueryBuilder('d')
            ->delete()
            ->where('d.id = :id')
            ->getQuery()
            ->execute(['id' => $id->toString()])
        ;
    }

    public function createQueryBuilder(): QueryBuilder
    {
        return $this->doctrineRepository->createByAuthorQueryBuilder(
            $this->userProvider->fetchCurrentUsersAuthor()
        );
    }

    public function findLatest(string $locale, string $label = 'title', int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $locale,
            $label,
            $limit
        );
    }

    public function entityExists(array $parameters, ?UuidInterface $id): bool
    {
        return $this->doctrineRepository->entityExists(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $parameters,
            $id
        );
    }
}
