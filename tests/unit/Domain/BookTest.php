<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;

class BookTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnBookCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a book without a title for author "book user"!');

        $author = $this->createMock(Author::class);
        $author->expects($this->once())->method('getUsername')->willReturn('book user');
        new Book($this->createMock(UuidInterface::class), '', $author);
    }

    public function testExceptionWhenEmptyTitleOnBookEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty title on book with id "book id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('book id');
        $book = new Book($id, 'book', $this->createMock(Author::class));
        $book->edit('', null);
    }
}
