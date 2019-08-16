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
use Talesweaver\Domain\Positionable;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Domain\ValueObject\Sort;
use Talesweaver\Integration\Doctrine\Repository\SceneRepository as DoctrineRepository;

final class SceneRepository implements Scenes
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
        return $this->doctrineRepository->findByIdForAuthor(
            $this->authorContext->getAuthor(),
            $id
        );
    }

    public function findByIds(array $ids): array
    {
        return $this->doctrineRepository
            ->createQueryBuilder('s')
            ->where('s.createdBy = :author')
            ->andWhere('s.id IN (:ids)')
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

    public function createBookListView(Book $book): array
    {
        return $this->doctrineRepository->createBookListView(
            $this->authorContext->getAuthor(),
            $book
        );
    }

    public function createListView(?Book $book, ?Chapter $chapter, ?Sort $sort): array
    {
        return $this->doctrineRepository->createListView(
            $this->authorContext->getAuthor(),
            $book,
            $chapter,
            $sort
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

    public function remove(Scene $scene): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $scene);
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

    public function createPublicationListPage(Scene $scene): array
    {
        return $this->doctrineRepository->createPublicationListPage(
            $this->authorContext->getAuthor(),
            $scene
        );
    }

    /**
     * @param Scene|Positionable $item
     */
    public function decreasePosition(Positionable $item): void
    {
        Assertion::isInstanceOf($item, Scene::class);
        if (0 === $item->getPosition()) {
            return;
        }

        /** @var Scene|null $itemBefore */
        $itemBefore = $this->doctrineRepository->findOneBy([
            'position' => $item->getPosition() - 1,
            'chapter' => $item->getChapter(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);

        if (null !== $itemBefore) {
            $itemBefore->setPosition($itemBefore->getPosition() + 1);
        }

        $item->setPosition($item->getPosition() - 1);
    }

    /**
     * @param Scene|Positionable $item
     */
    public function increasePosition(Positionable $item): void
    {
        Assertion::isInstanceOf($item, Scene::class);
        if (null === $item->getChapter()) {
            return;
        }

        $totalItemCount = $this->doctrineRepository->countForChapter($item->getChapter());
        if ($totalItemCount <= $item->getPosition()) {
            return;
        }

        /** @var Scene|null $itemAfter */
        $itemAfter = $this->doctrineRepository->findOneBy([
            'position' => $item->getPosition() + 1,
            'chapter' => $item->getChapter(),
            'createdBy' => $this->authorContext->getAuthor()
        ]);

        if (null !== $itemAfter) {
            $itemAfter->setPosition($itemAfter->getPosition() - 1);
        }

        $item->setPosition($item->getPosition() + 1);
    }

    public function supportsPositionable(Positionable $item): bool
    {
        return $item instanceof Scene;
    }
}
