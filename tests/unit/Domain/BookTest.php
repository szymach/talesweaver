<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\User;

class BookTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a book without a title for author "book user"!');

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getUsername')->willReturn('book user');
        new Book($this->createMock(UuidInterface::class), '', $user);
    }

    public function testExceptionWhenEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty title on book with id "book id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('book id');
        $book = new Book($id, 'book', $this->createMock(User::class));
        $book->edit('', null);
    }
}
