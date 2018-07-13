<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Scene;

class EventTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Cannot create an event without a name for author "event author id" and scene "uuid id"!'
        );

        $authorId = $this->createMock(UuidInterface::class);
        $authorId->expects($this->once())->method('toString')->willReturn('event author id');
        $author = $this->createMock(Author::class);
        $author->expects($this->once())->method('getId')->willReturn($authorId);

        $sceneId = $this->createMock(UuidInterface::class);
        $sceneId->expects($this->once())->method('toString')->willReturn('uuid id');
        $scene = $this->createMock(Scene::class);
        $scene->expects($this->once())->method('getId')->willReturn($sceneId);

        new Event(
            $this->createMock(UuidInterface::class),
            '',
            $this->createMock(JsonSerializable::class),
            $scene,
            $author
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
            $this->createMock(Author::class)
        );

        $event->edit('', $model);
    }
}
