<?php

declare(strict_types=1);

namespace Domain\Tests\Entity;

use Assert\InvalidArgumentException;
use Domain\Entity\Chapter;
use Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChapterTest extends TestCase
{
    public function testEmptyTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create a chapter without a title for author ""!');

        new Chapter(Uuid::uuid4(), '', null, $this->createMock(User::class));
    }

    public function testEmptyTitleOnEdit()
    {
        $id = Uuid::uuid4();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Tried to set an empty title on chapter with id "%s"!', $id));

        $chapter = new Chapter($id, 'chapter', null, $this->createMock(User::class));
        $chapter->edit('', null);
    }
}
