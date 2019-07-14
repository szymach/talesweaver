<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Domain\ValueObject\Sort;

interface Scenes
{
    public function find(UuidInterface $id): ?Scene;
    public function createListView(?Book $book, ?Chapter $chapter, ?Sort $sort): array;
    public function createBookListView(Book $book): array;
    public function findLatest(int $limit = 3): array;
    public function findOneByTitle(ShortText $title): ?Scene;
    public function add(Scene $scene): void;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $chapterId): bool;
    public function createPublicationListPage(Scene $scene): array;
}
