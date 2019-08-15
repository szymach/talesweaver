<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Publication;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\Sort;

final class SceneRepository extends AutoWireableTranslatableRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scene::class);
    }

    public function persist(Scene $scene): void
    {
        $this->getEntityManager()->persist($scene);
        if (null !== $scene->getChapter()) {
            $this->getEntityManager()
                ->createQueryBuilder()
                ->update($this->getEntityName(), 's')
                ->set('s.position', 's.position + 1')
                ->where('s.chapter = :chapter')
                ->andWhere('s.createdBy = :createdBy')
                ->getQuery()
                ->execute(['chapter' => $scene->getChapter(), 'createdBy' => $scene->getCreatedBy()])
            ;
        }
    }

    public function remove(Author $author, Scene $scene): void
    {
        $this->createQueryBuilder('s')
            ->delete()
            ->where('s.id = :id')
            ->andWhere('s.createdBy = :createdBy')
            ->getQuery()
            ->execute(['id' => $scene->getId(), 'createdBy' => $author])
        ;

        if (null !== $scene->getChapter()) {
            $this->getEntityManager()->createQueryBuilder()
                ->update($this->getEntityName(), 's')
                ->set('c.position', 's.position - 1')
                ->where('s.chapter = :chapter')
                ->andWhere('s.position > :position')
                ->andWhere('s.position > 0')
                ->andWhere('s.createdBy = :createdBy')
                ->getQuery()
                ->execute([
                    'chapter' => $scene->getChapter(),
                    'createdBy' => $author,
                    'position' => $scene->getPosition()
                ])
            ;
        }
    }

    public function findByIdForAuthor(Author $author, UuidInterface $id): ?Scene
    {
        return $this->createQueryBuilder('s')
            ->addSelect('t')
            ->addSelect('c')
            ->addSelect('ct')
            ->innerJoin('s.translations', 't', Join::WITH, 't.locale = :locale')
            ->leftJoin('s.chapter', 'c')
            ->leftJoin('c.translations', 'ct', Join::WITH, 'ct.locale = :locale')
            ->where('s.id = :id')
            ->andWhere('s.createdBy = :author')
            ->groupBy('s.id')
            ->setParameter('id', $id)
            ->setParameter('author', $author)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function createBookListView(Author $author, Book $book): array
    {
        $query = $this->getEntityManager()->getConnection()
            ->createQueryBuilder()
            ->select('s.id, st.title')
            ->from($this->getClassMetadata()->getTableName(), 's')
            ->innerJoin('s', 'scene_translation', 'st', 's.id = st.scene_id AND st.locale = :locale')
            ->innerJoin('s', 'chapter', 'c', 's.chapter_id = c.id')
            ->innerJoin('c', 'book', 'b', 'c.book_id = b.id AND b.id = :book')
            ->where('s.created_by_id = :author')
            ->orderBy('c.position')
            ->addOrderBy('s.position')
            ->setParameter('author', $author->getId())
            ->setParameter('book', $book->getId())
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;

        $statement = $query->execute();
        if (false === $statement instanceof Statement) {
            return [];
        }

        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
    }

    public function createListView(Author $author, ?Book $book, ?Chapter $chapter, ?Sort $sort): array
    {
        $query = $this->getEntityManager()->getConnection()
            ->createQueryBuilder()
            ->select('s.id, st.title AS title')
            ->addSelect('ct.title AS chapter')
            ->addSelect('bt.title AS book')
            ->from($this->getClassMetadata()->getTableName(), 's')
            ->innerJoin('s', 'scene_translation', 'st', 's.id = st.scene_id AND st.locale = :locale')
            ->leftJoin('s', 'chapter', 'c', 's.chapter_id = c.id')
            ->leftJoin('c', 'chapter_translation', 'ct', 'c.id = ct.chapter_id AND ct.locale = :locale')
            ->leftJoin('c', 'book', 'b', 'c.book_id = b.id')
            ->leftJoin('b', 'book_translation', 'bt', 'b.id = bt.book_id AND bt.locale = :locale')
            ->where('s.created_by_id = :author')
            ->setParameter('author', $author->getId())
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;

        if (null !== $sort) {
            switch ($sort->getField()) {
                case 'title':
                    $query->orderBy('title', $sort->getDirection());
                    break;
                case 'chapter':
                    $query->orderBy('chapter', $sort->getDirection());
                    break;
                case 'book':
                    $query->orderBy('book', $sort->getDirection());
                    break;
                default:
                    $query->orderBy('bt.title', 'asc')->addOrderBy('ct.tile', 'asc')->addOrderBy('title', 'asc');
            }
        } elseif (null !== $book) {
            $query->orderBy('c.position')->addOrderBy('s.position');
        } elseif (null !== $chapter) {
            $query->orderBy('s.position');
        }

        if (null !== $book) {
            $query->andWhere('b.id = :book')->setParameter('book', $book->getId());
        }

        if (null !== $chapter) {
            $query->andWhere('c.id = :chapter')->setParameter('chapter', $chapter->getId());
        }

        $statement = $query->execute();
        if (false === $statement instanceof Statement) {
            return [];
        }

        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
    }

    public function findLatest(Author $author, int $limit): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('(CASE WHEN e.updatedAt IS NOT NULL THEN e.updatedAt ELSE e.createdAt END) AS date')
            ->addSelect('(CASE WHEN e.updatedAt IS NOT NULL THEN 1 ELSE 0 END) AS updated')
            ->addSelect('e.id')
            ->addSelect('t.title AS label')
            ->from($this->getEntityName(), 'e')
            ->join('e.translations', 't', Join::WITH, 't.locale = :locale')
            ->where('e.createdBy = :author')
            ->orderBy('date', 'DESC')
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
            ->setParameter('author', $author)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function existsStandaloneWithTitle(Author $author, string $title, ?UuidInterface $id): bool
    {
        $qb = $this->countForTitleQb($author, $title)->andWhere('s.chapter IS NULL');

        if (null !== $id) {
            $qb->andWhere('s.id != :id')->setParameter('id', $id);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function existsAssignedWithTitle(
        Author $author,
        string $title,
        UuidInterface $chapterId,
        ?UuidInterface $id
    ): bool {
        $qb = $this->countForTitleQb($author, $title)
            ->innerJoin('s.chapter', 'c', Join::WITH, 'c.id = :chapterId')
            ->setParameter('chapterId', $chapterId)
        ;

        if (null !== $id) {
            $qb->andWhere('c.id != :id')->setParameter('id', $id);
        }

        return 0 !== (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function createPublicationListPage(Author $author, Scene $scene): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p.id, p.title, p.createdAt, p.locale, p.visible')
            ->from(Publication::class, 'p')
            ->innerJoin(Scene::class, 's', Join::WITH, 'p MEMBER OF s.publications AND s = :scene')
            ->where('s.createdBy = :author')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('author', $author)
            ->setParameter('scene', $scene)
            ->getQuery()
            ->getResult()
        ;
    }

    private function countForTitleQb(Author $author, string $title): QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(s.id)')
            ->from($this->getEntityName(), 's')
            ->join('s.translations', 't', Join::WITH, 't.locale = :locale AND t.title = :title')
            ->where('s.createdBy = :author')
            ->setParameter('author', $author)
            ->setParameter('title', $title)
            ->setParameter('locale', $this->getTranslatableListener()->getLocale())
        ;
    }
}
