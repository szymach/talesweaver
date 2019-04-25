<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Application\Security\AuthorContext;
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

    public function createListView(): array
    {
        $statement = $this->manager->getConnection()
            ->createQueryBuilder()
            ->select('s.id, st.title AS title')
            ->addSelect('ct.title AS chapter')
            ->addSelect('bt.title AS book')
            ->from('scene', 's')
            ->innerJoin('s', 'scene_translation', 'st', 's.id = st.scene_id AND st.locale = :locale')
            ->leftJoin('s', 'chapter', 'c', 's.chapter_id = c.id')
            ->leftJoin('c', 'chapter_translation', 'ct', 'c.id = ct.chapter_id AND ct.locale = :locale')
            ->leftJoin('c', 'book', 'b', 'c.book_id = b.id')
            ->leftJoin('b', 'book_translation', 'bt', 'b.id = bt.book_id AND bt.locale = :locale')
            ->where('s.created_by_id = :author')
            ->setParameter('author', $this->authorContext->getAuthor()->getId())
            ->setParameter('locale', $this->translatableListener->getLocale())
            ->orderBy('c.book_id')
            ->orderBy('s.chapter_id')
            ->addOrderBy('st.title')
            ->execute()
        ;

        if (null === $statement) {
            return [];
        }

        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
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

    public function findStandalone(): array
    {
        return $this->doctrineRepository->findStandaloneForAuthor(
            $this->authorContext->getAuthor()
        );
    }

    public function findForChapter(Chapter $chapter): array
    {
        return $this->doctrineRepository->findForAuthorAndChapter(
            $this->authorContext->getAuthor(),
            $chapter
        );
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

    public function firstCharacterOccurence(UuidInterface $id): string
    {
        $author = $this->authorContext->getAuthor();
        $result = $this->doctrineRepository->firstCharacterOccurence($author, $id);
        if (null === $result) {
            throw new AccessDeniedException(sprintf(
                'Character with id "%s" does not belong to user "%s"',
                $id->toString(),
                $author->getId()->toString()
            ));
        }

        return $result;
    }

    public function firstItemOccurence(UuidInterface $id): string
    {
        $author = $this->authorContext->getAuthor();
        $result = $this->doctrineRepository->firstItemOccurence($author, $id);
        if (null === $result) {
            throw new AccessDeniedException(sprintf(
                'Item with id "%s" does not belong to user "%s"',
                $id->toString(),
                $author->getId()->toString()
            ));
        }

        return $result;
    }

    public function firstLocationOccurence(UuidInterface $id): string
    {
        $author = $this->authorContext->getAuthor();
        $result = $this->doctrineRepository->firstLocationOccurence($author, $id);
        if (null === $result) {
            throw new AccessDeniedException(sprintf(
                'Location with id "%s" does not belong to user "%s"',
                $id->toString(),
                $author->getId()->toString()
            ));
        }

        return $result;
    }
}
