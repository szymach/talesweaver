<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Locations;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Repository\LocationRepository as DoctrineRepository;

final class LocationRepository implements Locations
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

    public function find(UuidInterface $id): ?Location
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function add(Location $location): void
    {
        $this->doctrineRepository->persist($location);
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

    public function entityExists(string $name, ?UuidInterface $id, ?Scene $scene): bool
    {
        if (null !== $scene) {
            $exists = $this->doctrineRepository->existsForSceneWithName(
                $this->authorContext->getAuthor(),
                $name,
                $scene
            );
        } elseif (null !== $id) {
            $exists = $this->doctrineRepository->nameConflictsWithRelated(
                $this->authorContext->getAuthor(),
                $name,
                $id
            );
        } else {
            throw new RuntimeException(
                "Neither location nor scene provided for name \"{$name}\"."
            );
        }

        return $exists;
    }
}
