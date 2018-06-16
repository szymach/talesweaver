<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert\InvalidArgumentException;
use Domain\Entity\Scene;
use Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class SceneTest extends TestCase
{
    public function testExceptionWhenEmptyTitleOnCreation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a scene without a title for author "user"!');

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getUsername')->willReturn('user');
        new Scene($this->createMock(UuidInterface::class), '', null, $user);
    }

    public function testExceptionWhenEmptyTitleOnEdit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tried to set an empty title on scene with id "scene id"!');

        $id = $this->createMock(UuidInterface::class);
        $id->expects($this->once())->method('toString')->willReturn('scene id');
        $scene = new Scene($id, 'scene', null, $this->createMock(User::class));
        $scene->edit('', null, null);
    }
}
