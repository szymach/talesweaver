<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert\InvalidArgumentException;
use Domain\Entity\Book;
use Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class BookTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a book without a title for author ""!');

        new Book(Uuid::uuid4(), '', $this->createMock(User::class));
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Tried to set an empty title on book with id "%s"!', $id));

        $book = new Book($id, 'book', $this->createMock(User::class));
        $book->edit('', null);
    }
}
