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
}
