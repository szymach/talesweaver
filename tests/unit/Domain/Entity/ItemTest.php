<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert;
use Domain\Entity\Item;
use Domain\Entity\Scene;
use Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ItemTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create an item without a name for author ""!');

        new Item(
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
            'Avatar file must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "string"'
        );

        new Item(
            Uuid::uuid4(),
            $this->createMock(Scene::class),
            'item',
            null,
            'text',
            $this->createMock(User::class)
        );
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Tried to set an empty name on item with id "%s"!', $id)
        );

        $item = new Item(
            $id,
            $this->createMock(Scene::class),
            'item',
            null,
            null,
            $this->createMock(User::class)
        );
        $item->edit('', null, null);
    }
}
