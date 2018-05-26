<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert\InvalidArgumentException;
use Domain\Entity\Event;
use Domain\Entity\Scene;
use Domain\Entity\User;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class EventTest extends TestCase
{
    public function testEmptyTitle()
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getId')->willReturn(1);
        $sceneId = $this->createMock(UuidInterface::class);
        $sceneId->expects($this->once())->method('toString')->willReturn('uuid id');
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->once())->method('getId')->willReturn($sceneId);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Cannot create an event without a name for author "1" and scene "uuid id"!'
        );

        new Event(
            $this->createMock(UuidInterface::class),
            '',
            $this->createMock(JsonSerializable::class),
            $scene,
            $user
        );
    }

    public function testEmptyTitleOnEdit()
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