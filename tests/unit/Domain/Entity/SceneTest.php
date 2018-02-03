<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert\InvalidArgumentException;
use Domain\Entity\Scene;
use Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SceneTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a scene without a title for author ""!');

        new Scene(Uuid::uuid4(), '', null, $this->createMock(User::class));
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Tried to set an empty title on scene with id "%s"!', $id));

        $book = new Scene($id, 'scene', null, $this->createMock(User::class));
        $book->edit('', null, null);
    }
}
