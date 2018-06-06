<?php

declare(strict_types=1);

namespace Domain\Tests\Entity\Character;

use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\Character;
use Domain\Entity\Scene;
use Domain\Entity\User;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

class RelationsTest extends TestCase
{
    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "unassigned scene id" is inconsistent with other scenes from character "characters id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects($this->exactly(1))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects($this->once())->method('toString')->willReturn('unassigned scene id');
        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects($this->once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects($this->exactly(1))->method('getChapter')->willReturn(null);

        $characterId = $this->createMock(UuidInterface::class);
        $characterId->expects($this->once())->method('toString')->willReturn('characters id');
        $character = new Character(
            $characterId,
            $sceneAssigned,
            'Character with inconsistent scenes',
            '',
            null,
            $this->createMock(User::class)
        );
        $character->addScene($sceneAssigned);
        $character->addScene($sceneUnassigned);
    }

    public function testExceptionWhenTheNewChapterHasADifferentBook()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Scene "scene with a book id" is inconsistent with other scenes from character "character with a book id"'
        );

        // Scene 1
        $book = $this->createMock(Book::class);
        $chapterWithABook = $this->createMock(Chapter::class);
        $chapterWithABook->expects($this->once())->method('getBook')->willReturn($book);

        $sceneWithABook = $this->createMock(Scene::class);
        $sceneWithABook->expects($this->once())->method('getChapter')->willReturn($chapterWithABook);

        // Scene 2
        $differentBook = $this->createMock(Book::class);

        $chapterWithADifferentBook = $this->createMock(Chapter::class);
        $chapterWithADifferentBook->expects($this->once())->method('getBook')->willReturn($differentBook);

        $sceneWithADifferentBookId = $this->createMock(UuidInterface::class);
        $sceneWithADifferentBookId->expects($this->once())->method('toString')->willReturn('scene with a book id');
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects($this->once())->method('getId')->willReturn($sceneWithADifferentBookId);
        $sceneWithADifferentBook->expects($this->once())
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $characterId = $this->createMock(UuidInterface::class);
        $characterId->expects($this->once())->method('toString')->willReturn('character with a book id');
        $character = new Character(
            $characterId,
            $sceneWithABook,
            'Character with inconsistent chapters',
            '',
            null,
            $this->createMock(User::class)
        );
        $character->addScene($sceneWithADifferentBook);
    }
}
