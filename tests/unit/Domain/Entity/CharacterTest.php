<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert;
use Domain\Entity\Character;
use Domain\Entity\Scene;
use Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CharacterTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a character without a name for author ""!');

        new Character(Uuid::uuid4(), $this->createMock(Scene::class), '', null, null, $this->createMock(User::class));
    }

    public function testIncorrectAvatar()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Avatar file must be either of instance "FSi\DoctrineExtensions\Uploadable\File" or "SplFileInfo", got "integer"'
        );

        new Character(
            Uuid::uuid4(),
            $this->createMock(Scene::class),
            'character',
            null,
            1,
            $this->createMock(User::class)
        );
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Tried to set an empty name on character with id "%s"!', $id));

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
}
