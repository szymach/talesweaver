<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Doctrine\Repository\SceneRepository as DoctrineRepository;

class SceneRepository implements Scenes
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
        TranslatableListener $translatableListener,
        DoctrineRepository $doctrineRepository,
        AuthorContext $authorContext
    ) {
        $this->manager = $manager;
        $this->doctrineRepository = $doctrineRepository;
        $this->translatableListener = $translatableListener;
        $this->authorContext = $authorContext;
    }

    public function find(UuidInterface $id): ?Scene
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function createBookListView(Book $book): array
    {
        return $this->doctrineRepository->createBookListView(
            $this->authorContext->getAuthor(),
            $book
        );
    }

    public function createListView(?Chapter $chapter): array
    {
        return $this->doctrineRepository->createListView(
            $this->authorContext->getAuthor(),
            $chapter
        );
    }

    public function findOneByTitle(ShortText $title): ?Scene
    {
        return $this->doctrineRepository->createTranslatableQueryBuilder('s')
            ->where('t.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function add(Scene $scene): void
    {
        $this->doctrineRepository->persist($scene);
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

    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $chapterId): bool
    {
        if (null !== $chapterId) {
            $exists = $this->doctrineRepository->existsAssignedWithTitle(
                $this->authorContext->getAuthor(),
                $title,
                $chapterId,
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
