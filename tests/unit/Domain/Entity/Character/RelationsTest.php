<?php

declare(strict_types=1);

namespace Domain\Tests\Entity\Character;

use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\Character;
use Domain\Entity\Scene;
use Domain\Entity\User;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class RelationsTest extends TestCase
{
    public function testChapterIsAddedWhenSceneHasOne()
    {
        $chapter = $this->createMock(Chapter::class);
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->exactly(2))->method('getChapter')->willReturn($chapter);

        $currentScene = $this->createMock(Scene::class);
        $currentScene->expects($this->once())->method('addCharacter')->with($this->isInstanceOf(Character::class));

        $character = new Character(
            $this->createMock(UuidInterface::class),
            $currentScene,
            'Chapter with two scenes',
            '',
            null,
            $this->createMock(User::class)
        );

        $this->assertNotContains($scene, $character->getScenes()->toArray());
        $this->assertNotContains($chapter, $character->getChapters()->toArray());
        $character->addScene($scene);
        $this->assertContains($scene, $character->getScenes()->toArray());
        $this->assertContains($chapter, $character->getChapters()->toArray());
    }

    public function testChapterIsRemovedWhenNoRelatedScenesAreLeft()
    {
        $chapter = $this->createMock(Chapter::class);
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->exactly(4))->method('getChapter')->willReturn($chapter);

        $character = new Character(
            $this->createMock(UuidInterface::class),
            $scene,
            'Chapter with a book',
            '',
            null,
            $this->createMock(User::class)
        );
        // Since the character would be added to the scene in the constructor via
        // $scene->addCharacter(), calling it on a mock scene object does not
        // properly call $character->addScene() and the chapter is not added. Hence
        // manual call
        $character->addScene($scene);

        $this->assertContains($chapter, $character->getChapters()->toArray());
        $character->removeScene($scene);
        $this->assertNotContains($chapter, $character->getChapters()->toArray());
    }

    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "unassigned scene id" is inconsistent with other scenes from character "characters id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects($this->exactly(2))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects($this->once())->method('toString')->willReturn('unassigned scene id');
        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects($this->once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects($this->exactly(1))->method('getChapter')->willReturn(null);

        $characterId = $this->createMock(UuidInterface::class);
        $characterId->expects($this->once())->method('toString')->willReturn('characters id');
        $character = new Character(
            $characterId,
            $sceneAssigned,
            'Character with inconsistent scenes',
            '',
            null,
            $this->createMock(User::class)
        );
        $character->addScene($sceneAssigned);
        $character->addScene($sceneUnassigned);
    }

    public function testExceptionWhenNewChapterHasNoBookAndCurrentOnesDo()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Chapter "chapter with a different book id" is inconsistent with'
            . ' character\'s "character with a book id" chapters'
        );

        $book = $this->createMock(Book::class);
        $chapterWithABook = $this->createMock(Chapter::class);
        $chapterWithABook->expects($this->once())
            ->method('addCharacter')
            ->with($this->isInstanceOf(Character::class))
        ;
        $chapterWithABook->expects($this->exactly(2))->method('getBook')->willReturn($book);

        $sceneWithABook = $this->createMock(Scene::class);
        $sceneWithABook->expects($this->exactly(2))->method('getChapter')->willReturn($chapterWithABook);

        $differentBook = $this->createMock(Book::class);
        $chapterWithADifferentBookId = $this->createMock(UuidInterface::class);
        $chapterWithADifferentBookId->expects($this->once())
            ->method('toString')
            ->willReturn('chapter with a different book id')
        ;
        $chapterWithADifferentBook = $this->createMock(Chapter::class);
        $chapterWithADifferentBook->expects($this->once())->method('getId')->willReturn($chapterWithADifferentBookId);
        $chapterWithADifferentBook->expects($this->once())->method('getBook')->willReturn($differentBook);
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects($this->exactly(3))
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $characterId = $this->createMock(UuidInterface::class);
        $characterId->expects($this->once())->method('toString')->willReturn('character with a book id');
        $character = new Character(
            $characterId,
            $sceneWithABook,
            'Character with inconsistent chapters',
            '',
            null,
            $this->createMock(User::class)
        );
        $character->addScene($sceneWithABook);
        $character->addScene($sceneWithADifferentBook);
    }

    public function testBookIsSetWhenChapterHasOne()
    {
        $book = $this->createMock(Book::class);
        $chapter = $this->createMock(Chapter::class);
        $chapter->expects($this->exactly(2))->method('getBook')->willReturn($book);

        $scene = $this->createMock(Scene::class);
        $scene->expects($this->exactly(2))->method('getChapter')->willReturn($chapter);

        $character = new Character(
            $this->createMock(UuidInterface::class),
            $this->createMock(Scene::class),
            'Chapter with a book',
            '',
            null,
            $this->createMock(User::class)
        );

        $this->assertNull($character->getBook());
        $character->addScene($scene);
        $this->assertEquals($book, $character->getBook());
    }

    public function testBookIsRemovedWhenNoRelatedChapterIsLeft()
    {
        $book = $this->createMock(Book::class);
        $chapter = $this->createMock(Chapter::class);
        $chapter->expects($this->exactly(4))->method('getBook')->willReturn($book);

        $scene = $this->createMock(Scene::class);
        $scene->expects($this->exactly(4))->method('getChapter')->willReturn($chapter);

        $character = new Character(
            $this->createMock(UuidInterface::class),
            $scene,
            'Chapter with a book',
            '',
            null,
            $this->createMock(User::class)
        );
        $character->addScene($scene);
        $character->removeScene($scene);
        $this->assertNull($character->getBook());
    }
}
