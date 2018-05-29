<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert;
use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\Character;
use Domain\Entity\Scene;
use Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class CharacterTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a character without a name for user "1"!');

        $author = $this->createMock(User::class);
        $author->expects($this->once())->method('getId')->willReturn(1);
        new Character(
            $this->createMock(UuidInterface::class),
            $this->createMock(Scene::class),
            '',
            null,
            null,
            $author
        );
    }

    public function testIncorrectAvatarOnNewEntity()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Character\'s "some uuid" avatar must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "integer"'
        );

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('some uuid');
        new Character(
            $id,
            $this->createMock(Scene::class),
            'character',
            null,
            1,
            $this->createMock(User::class)
        );
    }

    public function testIncorrectAvatarOnExistingEntity()
    {

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Character\'s "some edited uuid" avatar must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "integer"'
        );

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->exactly(2))->method('toString')->willReturn('some edited uuid');
        $character = new Character(
            $id,
            $this->createMock(Scene::class),
            'character',
            null,
            null,
            $this->createMock(User::class)
        );
        $character->edit('character', '', 34);
    }

    public function testEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty name on character with id "some id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('some id');
        $character = new Character(
            $id,
            $this->createMock(Scene::class),
            'character',
            null,
            null,
            $this->createMock(User::class)
        );
        $character->edit('', null, null);
    }

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
