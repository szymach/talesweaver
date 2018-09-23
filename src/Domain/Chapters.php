<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;

interface Chapters extends LatestChangesAwareRepository
{
    public function find(UuidInterface $id): ?Chapter;
    public function findForBook(Book $book): array;
    public function findAll(): array;
    public function findStandalone(): array;
    public function add(Chapter $chapter): void;
    public function remove(UuidInterface $id): void;
}
