<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\ValueObject\ShortText;

interface Scenes extends LatestChangesAwareRepository
{
    public function find(UuidInterface $id): ?Scene;
    public function findAll(): array;
    public function findForChapter(Chapter $chapter): array;
    public function findStandalone(): array;
    public function findOneByTitle(ShortText $title): ?Scene;
    public function firstCharacterOccurence(UuidInterface $id);
    public function firstItemOccurence(UuidInterface $id);
    public function firstLocationOccurence(UuidInterface $id);
    public function add(Scene $scene): void;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $chapterId): bool;
}
