<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chapter;
use App\Repository\Doctrine\SceneRepository as DoctrineRepository;
use App\Repository\Interfaces\LatestChangesAwareRepository;
use App\Security\UserProvider;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SceneRepository implements LatestChangesAwareRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var UserProvider
     */
    private $userProvider;

    public function __construct(
        DoctrineRepository $doctrineRepository,
        UserProvider $userProvider
    ) {
        $this->doctrineRepository = $doctrineRepository;
        $this->userProvider = $userProvider;
    }

    public function createStandaloneQueryBuilder(): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserStandaloneQueryBuilder(
            $this->userProvider->fetchCurrentUser()
        );
    }

    public function createForChapterQb(Chapter $chapter): QueryBuilder
    {
        return $this->doctrineRepository->byCurrentUserForChapterQb(
            $this->userProvider->fetchCurrentUser(),
            $chapter
        );
    }

    public function findLatest(string $locale, string $label = 'title', int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUser(),
            $locale,
            $label,
            $limit
        );
    }

    public function entityExists(array $parameters, ?UuidInterface $id): bool
    {
        return $this->doctrineRepository->entityExists(
            $this->userProvider->fetchCurrentUser(),
            $parameters,
            $id
        );
    }


    public function firstCharacterOccurence(UuidInterface $id): string
    {
        $currentUser = $this->userProvider->fetchCurrentUser();
        $result = $this->doctrineRepository->firstCharacterOccurence(
            $currentUser,
            $id
        );
        if (!$result) {
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
        $currentUser = $this->userProvider->fetchCurrentUser();
        $result = $this->doctrineRepository->firstItemOccurence(
            $currentUser,
            $id
        );
        if (!$result) {
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
        $currentUser = $this->userProvider->fetchCurrentUser();
        $result = $this->doctrineRepository->firstLocationOccurence(
            $currentUser,
            $id
        );
        if (!$result) {
            throw new AccessDeniedException(sprintf(
                'Location with id "%s" does not belong to user "%s"',
                $id,
                $currentUser->getId()
            ));
        }

        return $result;
    }
}
