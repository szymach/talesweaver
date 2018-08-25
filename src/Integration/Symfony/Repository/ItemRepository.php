<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\DoctrineRepository\ItemRepository as DoctrineRepository;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Items;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Integration\Symfony\Repository\Provider\UserProvider;

class ItemRepository implements Items, RequestSecuredRepository
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

    public function find(UuidInterface $id): ?Item
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->userProvider->fetchCurrentUsersAuthor()
        ]);
    }

    public function add(Item $item): void
    {
        $this->doctrineRepository->persist($item);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->userProvider->fetchCurrentUsersAuthor(), $id);
    }

    public function findForScene(Scene $scene): array
    {
        return $this->doctrineRepository->findForAuthorAndScene(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $scene
        );
    }

    public function findRelated(Scene $scene): array
    {
        return $this->doctrineRepository->findRelatedToScene(
            $this->userProvider->fetchCurrentUsersAuthor(),
            $scene
        );
    }

    public function entityExists(string $name, ?UuidInterface $id, ?UuidInterface $sceneId): bool
    {
        if (null !== $sceneId) {
            $exists = $this->doctrineRepository->existsForSceneWithName(
                $this->userProvider->fetchCurrentUsersAuthor(),
                $name,
                $sceneId
            );
        } else {
            $exists = $this->doctrineRepository->nameConflictsWithRelated(
                $this->userProvider->fetchCurrentUsersAuthor(),
                $name,
                $id
            );
        }

        return $exists;
    }
}
