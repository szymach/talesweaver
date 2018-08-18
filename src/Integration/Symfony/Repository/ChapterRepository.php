<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\DoctrineRepository\ChapterRepository as DoctrineRepository;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Integration\Symfony\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Symfony\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Symfony\Repository\Provider\UserProvider;

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
            'id' => $id->toString(),
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findBy([
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function findStandalone(): array
    {
        return $this->doctrineRepository->findBy([
            'book' => null,
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function findForBook(Book $book): array
    {
        return $this->doctrineRepository->findForAuthorAndBook(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $book
        );
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $limit
        );
    }

    public function add(Chapter $chapter): void
    {
        $this->doctrineRepository->persist($chapter);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->userProvider->fetchCurrentUsersAuthor(), $id);
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
