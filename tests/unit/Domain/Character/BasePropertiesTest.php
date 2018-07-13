<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\Entity\Character;

use Assert;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;

class BasePropertiesTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a character without a name for user "character id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('character id');
        $author = $this->createMock(Author::class);
        $author->expects($this->once())->method('getId')->willReturn($id);
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
            $this->createMock(Author::class)
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
            $this->createMock(Author::class)
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
            $this->createMock(Author::class)
        );
        $character->edit('', null, null);
    }

    public function testProperCreation()
    {
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->once())->method('addCharacter')->with($this->isInstanceOf(Character::class));

        $character = new Character(
            $this->createMock(UuidInterface::class),
            $scene,
            'Chapter properly created',
            '',
            null,
            $this->createMock(Author::class)
        );
        $this->assertContains($scene, $character->getScenes());
    }
}
