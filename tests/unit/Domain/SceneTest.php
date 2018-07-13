<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;

class SceneTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a scene without a title for author "user"!');

        $user = $this->createMock(Author::class);
        $user->expects($this->once())->method('getUsername')->willReturn('user');
        new Scene($this->createMock(UuidInterface::class), '', null, $user);
    }

    public function testExceptionWhenEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty title on scene with id "scene id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('scene id');
        $scene = new Scene($id, 'scene', null, $this->createMock(Author::class));
        $scene->edit('', null, null);
    }
}
