<?php

declare(strict_types=1);

namespace Domain\Tests\Entity\Character;

use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\Character;
use Domain\Entity\Scene;
use Domain\Entity\User;
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

    public function testBookIsSetWhenChapterHasOne()
    {
        $book = $this->createMock(Book::class);
        $chapter = $this->createMock(Chapter::class);
        $chapter->expects($this->exactly(4))->method('getBook')->willReturn($book);

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
        $chapter->expects($this->exactly(6))->method('getBook')->willReturn($book);

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
