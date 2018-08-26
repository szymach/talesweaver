<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Integration\Doctrine\Repository\ItemRepository as DoctrineRepository;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Items;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Repository\Interfaces\RequestSecuredRepository;
use Talesweaver\Application\Security\AuthorContext;

class ItemRepository implements Items, RequestSecuredRepository
{
    /**
     * @var DoctrineRepository
     */
    private $doctrineRepository;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    public function __construct(DoctrineRepository $doctrineRepository, AuthorContext $authorContext)
    {
        $this->doctrineRepository = $doctrineRepository;
        $this->authorContext = $authorContext;
    }

    public function getClassName(): string
    {
        return $this->doctrineRepository->getClassName();
    }

    public function find(UuidInterface $id): ?Item
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function add(Item $item): void
    {
        $this->doctrineRepository->persist($item);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $id);
    }

    public function findForScene(Scene $scene): array
    {
        return $this->doctrineRepository->findForAuthorAndScene(
            $this->authorContext->getAuthor(),
            $scene
        );
    }

    public function findRelated(Scene $scene): array
    {
        return $this->doctrineRepository->findRelatedToScene(
            $this->authorContext->getAuthor(),
            $scene
        );
    }

    public function entityExists(string $name, ?UuidInterface $id, ?UuidInterface $sceneId): bool
    {
        if (null !== $sceneId) {
            $exists = $this->doctrineRepository->existsForSceneWithName(
                $this->authorContext->getAuthor(),
                $name,
                $sceneId
            );
        } else {
            $exists = $this->doctrineRepository->nameConflictsWithRelated(
                $this->authorContext->getAuthor(),
                $name,
                $id
            );
        }

        return $exists;
    }
}
