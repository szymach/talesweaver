<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;

interface Events
{
    public function find(UuidInterface $id): ?Event;
    public function findForScene(Scene $scene): array;
    public function findInEventsById(UuidInterface $id);
    public function add(Event $event): void;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $name, ?UuidInterface $id, ?UuidInterface $sceneId): bool;
}
