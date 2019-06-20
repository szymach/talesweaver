<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

class ChapterTest extends TestCase
{
    public function testExceptionWhenCreatingWithDifferentBookAuthor()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Chapter for user "chapter author id" with title "Chapter" cannot be assigned to book'
            . ' "incorrect book id", whose author is "book author id"'
        );

        $chapterAuthorId = $this->createMock(UuidInterface::class);
        $chapterAuthorId->expects(self::exactly(1))->method('toString')->willReturn('chapter author id');
        $chapterAuthor = $this->createMock(Author::class);
        $chapterAuthor->expects(self::exactly(2))->method('getId')->willReturn($chapterAuthorId);

        $bookAuthorId = $this->createMock(UuidInterface::class);
        $bookAuthorId->expects(self::exactly(1))->method('toString')->willReturn('book author id');
        $bookAuthor = $this->createMock(Author::class);
        $bookAuthor->expects(self::exactly(2))->method('getId')->willReturn($bookAuthorId);

        $bookId = $this->createMock(UuidInterface::class);
        $bookId->expects(self::once())->method('toString')->willReturn('incorrect book id');
        $book = $this->createMock(Book::class);
        $book->expects(self::once())->method('getId')->willReturn($bookId);
        $book->expects(self::exactly(2))->method('getCreatedBy')->willReturn($bookAuthor);

        new Chapter(
            $this->createMock(UuidInterface::class),
            new ShortText('Chapter'),
            $book,
            $chapterAuthor
        );
    }

    public function testExceptionWhenEditingWithDifferentBookAuthor()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Chapter for user "chapter author id" with title "Chapter" cannot be'
            . ' assigned to book "incorrect book id", whose author is "new book author id"'
        );

        $chapterAuthorId = $this->createMock(UuidInterface::class);
        $chapterAuthorId->expects(self::once())->method('toString')->willReturn('chapter author id');
        $chapterAuthor = $this->createMock(Author::class);
        $chapterAuthor->expects(self::exactly(4))->method('getId')->willReturn($chapterAuthorId);

        $chapterBook = $this->createMock(Book::class);
        $chapterBook->expects(self::once())->method('getCreatedBy')->willReturn($chapterAuthor);

        $chapter = new Chapter(
            $this->createMock(UuidInterface::class),
            new ShortText('Chapter'),
            $chapterBook,
            $chapterAuthor
        );

        $newBookAuthorId = $this->createMock(UuidInterface::class);
        $newBookAuthorId->expects(self::once())->method('toString')->willReturn('new book author id');
        $newBookAuthor = $this->createMock(Author::class);
        $newBookAuthor->expects(self::exactly(2))->method('getId')->willReturn($newBookAuthorId);

        $newBookId = $this->createMock(UuidInterface::class);
        $newBookId->expects(self::once())->method('toString')->willReturn('incorrect book id');
        $newBook = $this->createMock(Book::class);
        $newBook->expects(self::once())->method('getId')->willReturn($newBookId);
        $newBook->expects(self::exactly(2))->method('getCreatedBy')->willReturn($newBookAuthor);

        $chapter->edit(new ShortText('Chapter'), $newBook);
    }
}
