<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\User;

class EventTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Cannot create an event without a name for author "1" and scene "uuid id"!'
        );

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getId')->willReturn(1);

        $sceneId = $this->createMock(UuidInterface::class);
        $sceneId->expects($this->once())->method('toString')->willReturn('uuid id');
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->once())->method('getId')->willReturn($sceneId);

        new Event(
            $this->createMock(UuidInterface::class),
            '',
            $this->createMock(JsonSerializable::class),
            $scene,
            $user
        );
    }

    public function testExceptionWhenEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty name on event with id "uuid id 2"!');

        $eventId = $this->createMock(UuidInterface::class);
        $eventId->expects($this->once())->method('toString')->willReturn('uuid id 2');
        $model = $this->createMock(JsonSerializable::class);
        $event = new Event(
            $eventId,
            'Title',
            $model,
            $this->createMock(Scene::class),
            $this->createMock(User::class)
        );

        $event->edit('', $model);
    }
}
