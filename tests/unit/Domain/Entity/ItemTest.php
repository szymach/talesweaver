<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert;
use Domain\Entity\Item;
use Domain\Entity\Scene;
use Domain\Entity\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class ItemTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create an item without a name for author "2"!');

        $author = $this->createMock(User::class);
        $author->expects($this->once())->method('getId')->willReturn(2);
        new Item(
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
            'Item\'s "item uuid" avatar must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "string"'
        );

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('item uuid');
        new Item(
            $id,
            $this->createMock(Scene::class),
            'item',
            null,
            'text',
            $this->createMock(User::class)
        );
    }

    public function testIncorrectAvatarOnExistingEntity()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Item\'s "edited uuid" avatar must be either of instance "FSi\DoctrineExtensions\Uploadable\File"'
            . ' or "SplFileInfo", got "string"'
        );

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->exactly(2))->method('toString')->willReturn('edited uuid');
        $item = new Item(
            $id,
            $this->createMock(Scene::class),
            'item',
            null,
            null,
            $this->createMock(User::class)
        );
        $item->edit('item', '', 'avatar');
    }

    public function testEmptyTitleOnEdit()
    {
        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('another item uuid');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty name on item with id "another item uuid"!');

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
