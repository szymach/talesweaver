<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert;
use Domain\Entity\Location;
use Domain\Entity\Scene;
use Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class LocationTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a location without a name for author ""!');

        new Location(
            Uuid::uuid4(),
            $this->createMock(Scene::class),
            '',
            null,
            null,
            $this->createMock(User::class)
        );
    }

    public function testIncorrectAvatar()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Avatar file must be either of instance "FSi\DoctrineExtensions\Uploadable\File" or "SplFileInfo", got "stdClass"'
        );

        new Location(
            Uuid::uuid4(),
            $this->createMock(Scene::class),
            'location',
            null,
            new stdClass(),
            $this->createMock(User::class)
        );
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Tried to set an empty name on location with id "%s"!', $id));

        $location = new Location(
            $id,
            $this->createMock(Scene::class),
            'charater',
            null,
            null,
            $this->createMock(User::class)
        );
        $location->edit('', null, null);
    }
}
