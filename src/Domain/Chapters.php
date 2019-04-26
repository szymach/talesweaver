<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\ValueObject\ShortText;

interface Chapters extends LatestChangesAwareRepository
{
    public function createListView(): array;
    public function find(UuidInterface $id): ?Chapter;
    public function findForBook(Book $book): array;
    public function findOneByTitle(ShortText $title): ?Chapter;
    public function findAll(): array;
    public function add(Chapter $chapter): void;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $title, ?UuidInterface $id, ?UuidInterface $bookId): bool;
}
