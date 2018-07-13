<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;

class ChapterTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a chapter without a title for author "chapter author"!');

        $author = $this->createMock(Author::class);
        $author->expects($this->once())->method('getUsername')->willReturn('chapter author');

        new Chapter($this->createMock(UuidInterface::class), '', null, $author);
    }

    public function testExceptionWhenEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty title on chapter with id "chapter id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('chapter id');
        $chapter = new Chapter($id, 'Chapter', null, $this->createMock(Author::class));
        $chapter->edit('', null);
    }

    public function testExceptionWhenCreatingWithDifferentBookAuthor()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Chapter for user "chapter author id" with title "Chapter" cannot be assigned to book'
            . ' "incorrect book id", whose author is "book author id"'
        );

        $chapterAuthorId = $this->createMock(UuidInterface::class);
        $chapterAuthorId->expects($this->exactly(1))->method('toString')->willReturn('chapter author id');
        $chapterAuthor = $this->createMock(Author::class);
        $chapterAuthor->expects($this->exactly(2))->method('getId')->willReturn($chapterAuthorId);

        $bookAuthorId = $this->createMock(UuidInterface::class);
        $bookAuthorId->expects($this->exactly(1))->method('toString')->willReturn('book author id');
        $bookAuthor = $this->createMock(Author::class);
        $bookAuthor->expects($this->exactly(2))->method('getId')->willReturn($bookAuthorId);

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
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Chapter for user "chapter author id" with title "Chapter" cannot be'
            . ' assigned to book "incorrect book id", whose author is "new book author id"'
        );

        $chapterAuthorId = $this->createMock(UuidInterface::class);
        $chapterAuthorId->expects($this->once())->method('toString')->willReturn('chapter author id');
        $chapterAuthor = $this->createMock(Author::class);
        $chapterAuthor->expects($this->exactly(4))->method('getId')->willReturn($chapterAuthorId);

        $chapterBook = $this->createMock(Book::class);
        $chapterBook->expects($this->once())->method('getCreatedBy')->willReturn($chapterAuthor);

        $chapter = new Chapter($this->createMock(UuidInterface::class), 'Chapter', $chapterBook, $chapterAuthor);

        $newBookAuthorId = $this->createMock(UuidInterface::class);
        $newBookAuthorId->expects($this->once())->method('toString')->willReturn('new book author id');
        $newBookAuthor = $this->createMock(Author::class);
        $newBookAuthor->expects($this->exactly(2))->method('getId')->willReturn($newBookAuthorId);

        $newBookId = $this->createMock(UuidInterface::class);
        $newBookId->expects($this->once())->method('toString')->willReturn('incorrect book id');
        $newBook = $this->createMock(Book::class);
        $newBook->expects($this->once())->method('getId')->willReturn($newBookId);
        $newBook->expects($this->exactly(2))->method('getCreatedBy')->willReturn($newBookAuthor);

        $chapter->edit('Chapter', $newBook);
    }
}
