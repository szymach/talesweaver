<?php

declare(strict_types=1);

namespace Domain\Tests\Entity\Item;

use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\Item;
use Domain\Entity\Scene;
use Domain\Entity\User;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class RelationsTest extends TestCase
{
    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "inconsistent scene id" is inconsistent with other scenes of item "some item id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects($this->exactly(1))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects($this->once())->method('toString')->willReturn('inconsistent scene id');

        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects($this->once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects($this->exactly(1))->method('getChapter')->willReturn(null);

        $itemId = $this->createMock(UuidInterface::class);
        $itemId->expects($this->once())->method('toString')->willReturn('some item id');
        $item = new Item(
            $itemId,
            $sceneAssigned,
            'Item with inconsistent scenes',
            '',
            null,
            $this->createMock(User::class)
        );
        $item->addScene($sceneUnassigned);
    }

    public function testExceptionWhenTheNewChapterHasADifferentBook()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "id of scene with a book" is inconsistent with other scenes of item "item"'
        );

        // Scene 1
        $book = $this->createMock(Book::class);
        $chapterWithABook = $this->createMock(Chapter::class);
        $chapterWithABook->expects($this->once())->method('getBook')->willReturn($book);

        $sceneWithABook = $this->createMock(Scene::class);
        $sceneWithABook->expects($this->once())->method('getChapter')->willReturn($chapterWithABook);

        // Scene 2
        $differentBook = $this->createMock(Book::class);

        $chapterWithADifferentBook = $this->createMock(Chapter::class);
        $chapterWithADifferentBook->expects($this->once())->method('getBook')->willReturn($differentBook);

        $sceneWithADifferentBookId = $this->createMock(UuidInterface::class);
        $sceneWithADifferentBookId->expects($this->once())->method('toString')->willReturn('id of scene with a book');
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects($this->once())->method('getId')->willReturn($sceneWithADifferentBookId);
        $sceneWithADifferentBook->expects($this->once())
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $itemId = $this->createMock(UuidInterface::class);
        $itemId->expects($this->once())->method('toString')->willReturn('item');
        $item = new Item(
            $itemId,
            $sceneWithABook,
            'Item with inconsistent chapters',
            '',
            null,
            $this->createMock(User::class)
        );
        $item->addScene($sceneWithADifferentBook);
    }
}