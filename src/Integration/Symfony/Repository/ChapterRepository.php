<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Assert\Assertion;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Domain\Positionable;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Domain\ValueObject\Sort;
use Talesweaver\Integration\Doctrine\Repository\ChapterRepository as DoctrineRepository;

final class ChapterRepository implements Chapters
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
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(
        EntityManagerInterface $manager,
        DoctrineRepository $doctrineRepository,
        TranslatableListener $translatableListener,
        AuthorContext $authorContext
    ) {
        $this->manager = $manager;
        $this->doctrineRepository = $doctrineRepository;
        $this->translatableListener = $translatableListener;
        $this->authorContext = $authorContext;
    }

    public function find(UuidInterface $id): ?Chapter
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id->toString(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findByIds(array $ids): array
    {
        return $this->doctrineRepository
            ->createQueryBuilder('c')
            ->where('c.createdBy = :author')
            ->andWhere('c.id IN (:ids)')
            ->setParameter('author', $this->authorContext->getAuthor())
            ->setParameter(
                'ids',
                array_map(
                    function (UuidInterface $id): string {
                        return $id->toString();
                    },
                    $ids
                )
            )
            ->getQuery()
            ->getResult()
        ;
    }

    public function createListView(?Book $book, ?Sort $sort): array
    {
        return $this->doctrineRepository->createListView(
            $this->authorContext->getAuthor(),
            $book,
            $sort
        );
    }

    public function findByBook(Book $book): array
    {
        return $this->doctrineRepository->findBy([
            'book' => $book,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findBy([
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function findOneByTitle(ShortText $title): ?Chapter
    {
        return $this->doctrineRepository->createTranslatableQueryBuilder('c')
            ->where('t.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findLatest(int $limit = 3): array
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

    public function remove(Chapter $chapter): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $chapter);
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

    public function createPublicationListPage(Chapter $chapter): array
    {
        return $this->doctrineRepository->createPublicationListPage(
            $this->authorContext->getAuthor(),
            $chapter
        );
    }

    /**
     * @param Chapter|Positionable $item
     */
    public function decreasePosition(Positionable $item): void
    {
        Assertion::isInstanceOf($item, Chapter::class);
        if (0 === $item->getPosition() || null === $item->getBook()) {
            return;
        }

        /** @var Chapter|null $itemBefore */
        $itemBefore = $this->doctrineRepository->findOneBy([
            'position' => $item->getPosition() - 1,
            'book' => $item->getBook(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);

        if (null !== $itemBefore) {
            $itemBefore->setPosition($itemBefore->getPosition() + 1);
        }

        $item->setPosition($item->getPosition() - 1);
    }

    /**
     * @param Chapter|Positionable $item
     */
    public function increasePosition(Positionable $item): void
    {
        Assertion::isInstanceOf($item, Chapter::class);
        if (null === $item->getBook()) {
            return;
        }

        $totalItemCount = $this->doctrineRepository->countForBook($item->getBook());
        if ($totalItemCount <= $item->getPosition()) {
            return;
        }

        /** @var Chapter|null $itemAfter */
        $itemAfter = $this->doctrineRepository->findOneBy([
            'position' => $item->getPosition() + 1,
            'book' => $item->getBook(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);

        if (null !== $itemAfter && 0 !== $itemAfter->getPosition()) {
            $itemAfter->setPosition($itemAfter->getPosition() - 1);
        }

        $item->setPosition($item->getPosition() + 1);
    }

    public function supportsPositionable(Positionable $item): bool
    {
        return $item instanceof Chapter;
    }
}
