<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\Entity\Location;

use Assert;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use stdClass;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;

class BasePropertiesTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a location without a name for author "location id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('location id');
        $author = $this->createMock(Author::class);
        $author->expects($this->once())->method('getId')->willReturn($id);
        new Location(
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
            'Location\'s "location\'s id" avatar must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "stdClass"'
        );

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('location\'s id');
        new Location(
            $id,
            $this->createMock(Scene::class),
            'location',
            null,
            new stdClass(),
            $this->createMock(Author::class)
        );
    }

    public function testIncorrectAvatarOnExistingEntity()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Location\'s "edited uuid" avatar must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "stdClass"'
        );

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->exactly(2))->method('toString')->willReturn('edited uuid');
        $location = new Location(
            $id,
            $this->createMock(Scene::class),
            'new location',
            null,
            null,
            $this->createMock(Author::class)
        );
        $location->edit('edited location', '', new stdClass());
    }

    public function testEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty name on location with id "edited location\'s id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('edited location\'s id');
        $location = new Location(
            $id,
            $this->createMock(Scene::class),
            'location',
            null,
            null,
            $this->createMock(Author::class)
        );
        $location->edit('', null, null);
    }

    public function testProperCreation()
    {
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->once())->method('addLocation')->with($this->isInstanceOf(Location::class));

        $location = new Location(
            $this->createMock(UuidInterface::class),
            $scene,
            'Location properly created',
            '',
            null,
            $this->createMock(Author::class)
        );
        $this->assertContains($scene, $location->getScenes());
    }
}
