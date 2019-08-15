<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Domain\ValueObject\Sort;

interface Chapters
{
    public function createListView(?Book $book, ?Sort $sort): array;
    public function find(UuidInterface $id): ?Chapter;
    public function findByIds(array $ids): array;
    public function findByBook(Book $book): array;
    public function findLatest(int $limit = 3): array;
    public function findOneByTitle(ShortText $title): ?Chapter;
    public function findAll(): array;
    public function add(Chapter $chapter): void;
    public function remove(Chapter $chapter): void;
    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $bookId): bool;
    public function createPublicationListPage(Chapter $book): array;
}
