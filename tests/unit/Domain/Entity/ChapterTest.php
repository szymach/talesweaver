<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert\InvalidArgumentException;
use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChapterTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a chapter without a title for author "1"!');

        $author = $this->createMock(User::class);
        $author->expects($this->once())->method('getId')->willReturn(1);

        new Chapter(Uuid::uuid4(), '', null, $author);
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Tried to set an empty title on chapter with id "%s"!', $id));

        $chapter = new Chapter($id, 'Chapter', null, $this->createMock(User::class));
        $chapter->edit('', null);
    }

    public function testCreatingWithDifferentBookAuthor()
    {
        $bookId = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Chapter for user "1" with title "Chapter" cannot be assigned to book "%s", whose author is "2"',
            $bookId->toString()
        ));

        $chapterAuthor = $this->createMock(User::class);
        $chapterAuthor->expects($this->exactly(2))->method('getId')->willReturn(1);

        $bookAuthor = $this->createMock(User::class);
        $bookAuthor->expects($this->once())->method('getId')->willReturn(2);

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

    public function testEditionWithDifferentBookAuthor()
    {
        $newBookId = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Chapter for user "1" with title "Chapter" cannot be assigned to book "%s", whose author is "2"',
            $newBookId->toString()
        ));

        $chapterAuthor = $this->createMock(User::class);
        $chapterAuthor->expects($this->exactly(4))->method('getId')->willReturn(1);

        $chapterBook = $this->createMock(Book::class);
        $chapterBook->expects($this->once())->method('getId')->willReturn($this->createMock(UuidInterface::class));
        $chapterBook->expects($this->exactly(2))->method('getCreatedBy')->willReturn($chapterAuthor);

        $chapterId = Uuid::uuid4();
        $chapter = new Chapter($chapterId, 'Chapter', $chapterBook, $chapterAuthor);

        $newBookAuthor = $this->createMock(User::class);
        $newBookAuthor->expects($this->once())->method('getId')->willReturn(2);

        $newBook = $this->createMock(Book::class);
        $newBook->expects($this->once())->method('getId')->willReturn($newBookId);
        $newBook->expects($this->exactly(2))->method('getCreatedBy')->willReturn($newBookAuthor);
        $chapter->edit('Chapter', $newBook);
    }
}
