<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\Entity;

use Codeception\Test\Unit;
use DomainException;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class LocationTest extends Unit
{
    public function testProperLocationCreation()
    {
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->once())->method('addLocation')->with($this->isInstanceOf(Location::class));

        $location = new Location(
            $this->createMock(UuidInterface::class),
            $scene,
            new ShortText('Location properly created'),
            LongText::fromNullableString('Location description'),
            null,
            $this->createMock(Author::class)
        );
        $this->assertContains($scene, $location->getScenes());
    }

    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "a scene id" is inconsistent with other scenes of location "location\'s id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects($this->exactly(1))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects($this->once())->method('toString')->willReturn('a scene id');

        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects($this->once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects($this->exactly(1))->method('getChapter')->willReturn(null);

        $locationId = $this->createMock(UuidInterface::class);
        $locationId->expects($this->once())->method('toString')->willReturn('location\'s id');
        $location = new Location(
            $locationId,
            $sceneAssigned,
            new ShortText('Location with inconsistent scenes'),
            null,
            null,
            $this->createMock(Author::class)
        );
        $location->addScene($sceneUnassigned);
    }

    public function testExceptionWhenTheNewChapterHasADifferentBook()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "scene id" is inconsistent with other scenes of location "location with an id"'
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
        $sceneWithADifferentBookId->expects($this->once())->method('toString')->willReturn('scene id');
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects($this->once())->method('getId')->willReturn($sceneWithADifferentBookId);
        $sceneWithADifferentBook->expects($this->once())
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $locationId = $this->createMock(UuidInterface::class);
        $locationId->expects($this->once())->method('toString')->willReturn('location with an id');
        $location = new Location(
            $locationId,
            $sceneWithABook,
            new ShortText('Location with inconsistent chapters'),
            null,
            null,
            $this->createMock(Author::class)
        );
        $location->addScene($sceneWithADifferentBook);
    }

    public function testNotRemovingFromOnlyScene(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Cannot remove location "location id" from scene "scene 1", because it is it\'s only scene!'
        );

        $chapter = $this->createMock(Chapter::class);

        $scene1 = $this->makeEmpty(Scene::class, [
            'getId' => $this->makeEmpty(UuidInterface::class, ['toString' => 'scene 1']),
            'getChapter' => $chapter
        ]);
        $scene2 = $this->makeEmpty(Scene::class, ['getChapter' => $chapter]);

        $location = new Location(
            $this->makeEmpty(UuidInterface::class, ['toString' => 'location id']),
            $scene1,
            new ShortText('Location'),
            null,
            null,
            $this->createMock(Author::class)
        );

        $location->addScene($scene2);

        $location->removeScene($scene2);
        $location->removeScene($scene1);
    }
}
