<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChapterTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a chapter without a title for author "chapter author"!');

        $author = $this->createMock(User::class);
        $author->expects($this->once())->method('getUsername')->willReturn('chapter author');

        new Chapter($this->createMock(UuidInterface::class), '', null, $author);
    }

    public function testExceptionWhenEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty title on chapter with id "chapter id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('chapter id');
        $chapter = new Chapter($id, 'Chapter', null, $this->createMock(User::class));
        $chapter->edit('', null);
    }

    public function testExceptionWhenCreatingWithDifferentBookAuthor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Chapter for user "1" with title "Chapter" cannot be assigned to book'
            . ' "incorrect book id", whose author is "2"'
        );

        $chapterAuthor = $this->createMock(User::class);
        $chapterAuthor->expects($this->exactly(2))->method('getId')->willReturn(1);

        $bookAuthor = $this->createMock(User::class);
        $bookAuthor->expects($this->exactly(2))->method('getId')->willReturn(2);

        $bookId = $this->createMock(UuidInterface::class);
        $bookId->expects($this->once())->method('toString')->willReturn('incorrect book id');
        $book = $this->createMock(Book::class);
        $book->expects($this->once())->method('getId')->willReturn($bookId);
        $book->expects($this->exactly(2))->method('getCreatedBy')->willReturn($bookAuthor);

        new Chapter(
            $this->createMock(UuidInterface::class),
            'Chapter',
            $book,
            $chapterAuthor
        );
    }

    public function testExceptionWhenEditingWithDifferentBookAuthor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Chapter for user "1" with title "Chapter" cannot be assigned to book '
            . '"incorrect book id", whose author is "2"'
        );

        $chapterAuthor = $this->createMock(User::class);
        $chapterAuthor->expects($this->exactly(5))->method('getId')->willReturn(1);

        $chapterBook = $this->createMock(Book::class);
        $chapterBook->expects($this->once())->method('getId')->willReturn($this->createMock(UuidInterface::class));
        $chapterBook->expects($this->exactly(2))->method('getCreatedBy')->willReturn($chapterAuthor);

        $chapter = new Chapter($this->createMock(UuidInterface::class), 'Chapter', $chapterBook, $chapterAuthor);

        $newBookAuthor = $this->createMock(User::class);
        $newBookAuthor->expects($this->once())->method('getId')->willReturn(2);

        $newBookId = $this->createMock(UuidInterface::class);
        $newBookId->expects($this->once())->method('toString')->willReturn('incorrect book id');
        $newBook = $this->createMock(Book::class);
        $newBook->expects($this->once())->method('getId')->willReturn($newBookId);
        $newBook->expects($this->exactly(2))->method('getCreatedBy')->willReturn($newBookAuthor);

        $chapter->edit('Chapter', $newBook);
    }
}
