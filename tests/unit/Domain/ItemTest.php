<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\Entity;

use Codeception\Test\Unit;
use DomainException;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class ItemTest extends Unit
{
    public function testProperItemCreation()
    {
        $scene = $this->createMock(Scene::class);
        $scene->expects(self::once())->method('addItem')->with(self::isInstanceOf(Item::class));

        $item = new Item(
            $this->createMock(UuidInterface::class),
            $scene,
            new ShortText('Item properly created'),
            LongText::fromNullableString('Item description'),
            null,
            $this->createMock(Author::class)
        );
        self::assertContains($scene, $item->getScenes());
    }

    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Scene "inconsistent scene id" is inconsistent with other scenes of item "some item id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects(self::exactly(1))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects(self::once())->method('toString')->willReturn('inconsistent scene id');

        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects(self::once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects(self::exactly(1))->method('getChapter')->willReturn(null);

        $itemId = $this->createMock(UuidInterface::class);
        $itemId->expects(self::once())->method('toString')->willReturn('some item id');
        $item = new Item(
            $itemId,
            $sceneAssigned,
            new ShortText('Item with inconsistent scenes'),
            null,
            null,
            $this->createMock(Author::class)
        );
        $item->addScene($sceneUnassigned);
    }

    public function testExceptionWhenTheNewChapterHasADifferentBook()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Scene "id of scene with a book" is inconsistent with other scenes of item "item"'
        );

        // Scene 1
        $book = $this->createMock(Book::class);
        $chapterWithABook = $this->createMock(Chapter::class);
        $chapterWithABook->expects(self::once())->method('getBook')->willReturn($book);

        $sceneWithABook = $this->createMock(Scene::class);
        $sceneWithABook->expects(self::once())->method('getChapter')->willReturn($chapterWithABook);

        // Scene 2
        $differentBook = $this->createMock(Book::class);

        $chapterWithADifferentBook = $this->createMock(Chapter::class);
        $chapterWithADifferentBook->expects(self::once())->method('getBook')->willReturn($differentBook);

        $sceneWithADifferentBookId = $this->createMock(UuidInterface::class);
        $sceneWithADifferentBookId->expects(self::once())->method('toString')->willReturn('id of scene with a book');
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects(self::once())->method('getId')->willReturn($sceneWithADifferentBookId);
        $sceneWithADifferentBook->expects(self::once())
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $itemId = $this->createMock(UuidInterface::class);
        $itemId->expects(self::once())->method('toString')->willReturn('item');
        $item = new Item(
            $itemId,
            $sceneWithABook,
            new ShortText('Item with inconsistent chapters'),
            null,
            null,
            $this->createMock(Author::class)
        );
        $item->addScene($sceneWithADifferentBook);
    }

    public function testNotRemovingFromOnlyScene(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Cannot remove item "item id" from scene "scene 1", because it is it\'s only scene!'
        );

        $chapter = $this->createMock(Chapter::class);

        $scene1 = $this->makeEmpty(Scene::class, [
            'getId' => $this->makeEmpty(UuidInterface::class, ['toString' => 'scene 1']),
            'getChapter' => $chapter
        ]);
        $scene2 = $this->makeEmpty(Scene::class, ['getChapter' => $chapter]);

        $item = new Item(
            $this->makeEmpty(UuidInterface::class, ['toString' => 'item id']),
            $scene1,
            new ShortText('Item'),
            null,
            null,
            $this->createMock(Author::class)
        );

        $item->addScene($scene2);

        $item->removeScene($scene2);
        $item->removeScene($scene1);
    }
}
