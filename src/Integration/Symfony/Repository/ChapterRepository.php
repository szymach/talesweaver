<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\Repository\RequestSecuredRepository;
use Talesweaver\Integration\Doctrine\Repository\ChapterRepository as DoctrineRepository;

class ChapterRepository implements Chapters, LatestChangesAwareRepository, RequestSecuredRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(DoctrineRepository $doctrineRepository, AuthorContext $authorContext)
    {
        $this->doctrineRepository = $doctrineRepository;
        $this->authorContext = $authorContext;
    }

    public function getClassName(): string
    {
        return $this->doctrineRepository->getClassName();
    }

    public function find(UuidInterface $id): ?Chapter
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id->toString(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findBy([
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findStandalone(): array
    {
        return $this->doctrineRepository->findBy([
            'book' => null,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findForBook(Book $book): array
    {
        return $this->doctrineRepository->findForAuthorAndBook(
            $this->authorContext->getAuthor(),
            $book
        );
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->authorContext->getAuthor(),
            $limit
        );
    }

    public function add(Chapter $chapter): void
    {
        $this->doctrineRepository->persist($chapter);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $id);
    }

    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $bookId): bool
    {
        if (null !== $bookId) {
            $exists = $this->doctrineRepository->existsAssignedWithTitle(
                $this->authorContext->getAuthor(),
                $title,
                $bookId,
                $id
            );
        } else {
            $exists = $this->doctrineRepository->existsStandaloneWithTitle(
                $this->authorContext->getAuthor(),
                $title,
                $id
            );
        }

        return $exists;
    }
}
