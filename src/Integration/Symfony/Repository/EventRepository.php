<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Repository;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Events;
use Talesweaver\Domain\Repository\RequestSecuredRepository;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Repository\EventRepository as DoctrineRepository;

class EventRepository implements Events, RequestSecuredRepository
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

    public function findInEventsById(UuidInterface $id): array
    {
        return $this->doctrineRepository->findInEventsById(
            $this->authorContext->getAuthor(),
            $id
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
