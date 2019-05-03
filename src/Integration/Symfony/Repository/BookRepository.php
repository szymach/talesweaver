<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Books;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Doctrine\Repository\BookRepository as DoctrineRepository;

class BookRepository implements Books
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(
        EntityManagerInterface $manager,
        DoctrineRepository $doctrineRepository,
        AuthorContext $authorContext
    ) {
        $this->manager = $manager;
        $this->doctrineRepository = $doctrineRepository;
        $this->authorContext = $authorContext;
    }

    public function add(Book $book): void
    {
        $this->doctrineRepository->persist($book);
    }

    public function findOneByTitle(ShortText $title): ?Book
    {
        return $this->doctrineRepository->createTranslatableQueryBuilder('b')
            ->where('t.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function find(UuidInterface $id): ?Book
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id->toString(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findByBook(Book $book): array
    {
        return $this->doctrineRepository->findBy([
            'book' => $book,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function createListView(): array
    {
        return $this->doctrineRepository->createListView($this->authorContext->getAuthor());
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findBy([
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $id);
    }

    public function findLatest(int $limit = 3): array
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
