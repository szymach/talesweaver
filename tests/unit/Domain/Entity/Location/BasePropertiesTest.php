<?php

declare(strict_types=1);

namespace Domain\Tests\Entity\Location;

use Assert;
use Domain\Entity\Location;
use Domain\Entity\Scene;
use Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use stdClass;

class BasePropertiesTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a location without a name for author "4"!');

        $author = $this->createMock(User::class);
        $author->expects($this->once())->method('getId')->willReturn(4);
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
            $this->createMock(User::class)
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
            $this->createMock(User::class)
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
            $this->createMock(User::class)
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
            $this->createMock(User::class)
        );
        $this->assertContains($scene, $location->getScenes());
    }
}
