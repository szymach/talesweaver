<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Events;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Repository\EventRepository as DoctrineRepository;

final class EventRepository implements Events
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

    public function find(UuidInterface $id): ?Event
    {
        return $this->doctrineRepository->findOneBy([
            'id' => $id,
            'createdBy' => $this->authorContext->getAuthor()
        ]);
    }

    public function add(Event $event): void
    {
        $this->doctrineRepository->persist($event);
    }

    public function remove(UuidInterface $id): void
    {
        $this->doctrineRepository->remove($this->authorContext->getAuthor(), $id);
    }

    public function findForScene(Scene $scene): array
    {
        return $this->doctrineRepository->findForScene(
            $this->authorContext->getAuthor(),
            $scene
        );
    }

    public function findForCharacter(Character $character): array
    {
        return $this->doctrineRepository->findForCharacter(
            $this->authorContext->getAuthor(),
            $character
        );
    }

    public function findForItem(Item $item): array
    {
        return $this->doctrineRepository->findForItem(
            $this->authorContext->getAuthor(),
            $item
        );
    }

    public function findForLocation(Location $location): array
    {
        return $this->doctrineRepository->findForLocation(
            $this->authorContext->getAuthor(),
            $location
        );
    }

    public function findNamesForScene(Scene $scene): array
    {
        return $this->doctrineRepository->findNamesForScene(
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
        } elseif (null !== $id) {
            $exists = $this->doctrineRepository->nameConflictsWithRelated(
                $this->authorContext->getAuthor(),
                $name,
                $id
            );
        } else {
            throw new RuntimeException('Neither event nor scene id provided');
        }

        return $exists;
    }
}
