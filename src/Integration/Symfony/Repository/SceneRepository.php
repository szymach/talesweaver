<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\DoctrineRepository\SceneRepository as DoctrineRepository;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\Scenes;
use Talesweaver\Integration\Symfony\Repository\Interfaces\LatestChangesAwareRepository;
use Talesweaver\Integration\Symfony\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Symfony\Repository\Provider\UserProvider;

class SceneRepository implements Scenes, LatestChangesAwareRepository, RequestSecuredRepository
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

    public function find(UuidInterface $id): ?Scene
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function findAll(): array
    {
        return $this->doctrineRepository->findAll();
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository
            ->createQueryBuilder('s')
            ->delete()
            ->where('s.id = :id')
            ->getQuery()
            ->execute(['id' => $id->toString()])
        ;
    }

    public function createStandaloneQueryBuilder(): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentAuthorStandaloneQueryBuilder(
            $this->userProvider->fetchCurrentUsersAuthor()
        );
    }

    public function createForChapterQb(Chapter $chapter): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentAuthorForChapterQb(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $chapter
        );
    }

    public function findLatest(string $locale, string $label = 'title', int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $locale,
            $label,
            $limit
        );
    }

    public function entityExists(array $parameters, ?UuidInterface $id): bool
    {
        return $this->doctrineRepository->entityExists(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $parameters,
            $id
        );
    }

    public function firstCharacterOccurence(UuidInterface $id): string
    {
        $currentUser = $this->userProvider->fetchCurrentUsersAuthor();
        $result = $this->doctrineRepository->firstCharacterOccurence($currentUser, $id);
        if (null === $result) {
            throw new AccessDeniedException(sprintf(
                'Character with id "%s" does not belong to user "%s"',
                $id,
                $currentUser->getId()
            ));
        }

        return $result;
    }

    public function firstItemOccurence(UuidInterface $id): string
    {
        $currentUser = $this->userProvider->fetchCurrentUsersAuthor();
        $result = $this->doctrineRepository->firstItemOccurence($currentUser, $id);
        if (null === $result) {
            throw new AccessDeniedException(sprintf(
                'Item with id "%s" does not belong to user "%s"',
                $id,
                $currentUser->getId()
            ));
        }

        return $result;
    }

    public function firstLocationOccurence(UuidInterface $id): string
    {
        $currentUser = $this->userProvider->fetchCurrentUsersAuthor();
        $result = $this->doctrineRepository->firstLocationOccurence($currentUser, $id);
        if (null === $result) {
            throw new AccessDeniedException(sprintf(
                'Location with id "%s" does not belong to user "%s"',
                $id,
                $currentUser->getId()
            ));
        }

        return $result;
    }
}
