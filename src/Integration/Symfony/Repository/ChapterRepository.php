<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Chapters;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Doctrine\Repository\ChapterRepository as DoctrineRepository;

class ChapterRepository implements Chapters
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

    public function findAll(): array
    {
        $statement = $this->manager->getConnection()
            ->createQueryBuilder()
            ->select('c.id, ct.title AS title')
            ->addSelect('bt.title AS book')
            ->from('chapter', 'c')
            ->leftJoin('c', 'chapter_translation', 'ct', 'c.id = ct.chapter_id AND ct.locale = :locale')
            ->leftJoin('c', 'book', 'b', 'c.book_id = b.id')
            ->leftJoin('b', 'book_translation', 'bt', 'b.id = bt.book_id AND bt.locale = :locale')
            ->where('c.created_by_id = :author')
            ->setParameter('author', $this->authorContext->getAuthor()->getId())
            ->setParameter('locale', $this->translatableListener->getLocale())
            ->orderBy('c.book_id')
            ->addOrderBy('ct.title')
            ->execute()
        ;

        if (null === $statement) {
            return [];
        }

        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
    }

    public function findForBook(Book $book): array
    {
        return $this->doctrineRepository->findForAuthorAndBook(
            $this->authorContext->getAuthor(),
            $book
        );
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
