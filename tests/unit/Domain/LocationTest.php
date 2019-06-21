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
        $scene->expects(self::once())->method('addLocation')->with(self::isInstanceOf(Location::class));

        $location = new Location(
            $this->createMock(UuidInterface::class),
            $scene,
            new ShortText('Location properly created'),
            LongText::fromNullableString('Location description'),
            null,
            $this->createMock(Author::class)
        );
        self::assertContains($scene, $location->getScenes());
    }

    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo()
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Scene "a scene id" is inconsistent with other scenes of location "location\'s id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects(self::exactly(1))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects(self::once())->method('toString')->willReturn('a scene id');

        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects(self::once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects(self::exactly(1))->method('getChapter')->willReturn(null);

        $locationId = $this->createMock(UuidInterface::class);
        $locationId->expects(self::once())->method('toString')->willReturn('location\'s id');
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
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Scene "scene id" is inconsistent with other scenes of location "location with an id"'
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
        $sceneWithADifferentBookId->expects(self::once())->method('toString')->willReturn('scene id');
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects(self::once())->method('getId')->willReturn($sceneWithADifferentBookId);
        $sceneWithADifferentBook->expects(self::once())
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $locationId = $this->createMock(UuidInterface::class);
        $locationId->expects(self::once())->method('toString')->willReturn('location with an id');
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
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Cannot remove location "location id" from scene "scene 1", because it is it\'s only scene!'
        );

        $chapter = $this->createMock(Chapter::class);

        /** @var UuidInterface $sceneId */
        $sceneId = $this->makeEmpty(UuidInterface::class, ['toString' => 'scene 1']);
        /** @var Scene $scene1 */
        $scene1 = $this->makeEmpty(Scene::class, [
            'getId' => $sceneId,
            'getChapter' => $chapter
        ]);
        /** @var Scene $scene2 */
        $scene2 = $this->makeEmpty(Scene::class, ['getChapter' => $chapter]);

        /** @var UuidInterface $id */
        $id = $this->makeEmpty(UuidInterface::class, ['toString' => 'location id']);
        $location = new Location(
            $id,
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
