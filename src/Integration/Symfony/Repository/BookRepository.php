<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\Repository\RequestSecuredRepository;
use Talesweaver\Integration\Doctrine\Repository\BookRepository as DoctrineRepository;

class BookRepository implements Books, LatestChangesAwareRepository, RequestSecuredRepository
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

    public function find(UuidInterface $id): ?Book
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

    public function add(Book $book): void
    {
        $this->doctrineRepository->persist($book);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $id);
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->authorContext->getAuthor(),
            $limit
        );
    }

    public function entityExists(string $title, ?UuidInterface $id): bool
    {
        return $this->doctrineRepository->entityExists(
            $this->authorContext->getAuthor(),
            $title,
            $id
        );
    }
}
