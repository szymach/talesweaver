<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Talesweaver\Integration\Doctrine\Repository\SceneRepository as DoctrineRepository;
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

    public function add(Scene $scene): void
    {
        $this->doctrineRepository->persist($scene);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->userProvider->fetchCurrentUsersAuthor(), $id);
    }

    public function findStandalone(): array
    {
        return $this->doctrineRepository->findStandaloneForAuthor(
            $this->userProvider->fetchCurrentUsersAuthor()
        );
    }

    public function findForChapter(Chapter $chapter): array
    {
        return $this->doctrineRepository->findForAuthorAndChapter(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $chapter
        );
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->doctrineRepository->findLatest(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $limit
        );
    }

    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $chapterId): bool
    {
        if (null !== $chapterId) {
            $exists = $this->doctrineRepository->existsAssignedWithTitle(
                $this->userProvider->fetchCurrentUsersAuthor(),
                $title,
                $chapterId,
                $id
            );
        } else {
            $exists = $this->doctrineRepository->existsStandaloneWithTitle(
                $this->userProvider->fetchCurrentUsersAuthor(),
                $title,
                $id
            );
        }

        return $exists;
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
