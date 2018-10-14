<?php

declare(strict_types=1);

namespace Talesweaver\Domain;

use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Repository\LatestChangesAwareRepository;
use Talesweaver\Domain\ValueObject\ShortText;

interface Books extends LatestChangesAwareRepository
{
    public function add(Book $book): void;
    public function findOneByTitle(ShortText $title): ?Book;
    public function find(UuidInterface $id): ?Book;
    public function findAll(): array;
    public function remove(UuidInterface $id): void;
    public function entityExists(string $title, ?UuidInterface $id): bool;
}
