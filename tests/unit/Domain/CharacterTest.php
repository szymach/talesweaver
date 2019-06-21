<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\Entity;

use Codeception\Test\Unit;
use DomainException;
use Ramsey\Uuid\UuidInterface;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CharacterTest extends Unit
{
    public function testProperCharacterCreation(): void
    {
        $scene = $this->createMock(Scene::class);
        $scene->expects(self::once())->method('addCharacter')->with(self::isInstanceOf(Character::class));

        $character = new Character(
            $this->createMock(UuidInterface::class),
            $scene,
            new ShortText('Character properly created'),
            LongText::fromNullableString('Character description'),
            null,
            $this->createMock(Author::class)
        );
        self::assertContains($scene, $character->getScenes());
    }

    public function testExceptionWhenNewSceneHasNoChapterAndCurrentOnesDo(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Scene "unassigned scene id" is inconsistent with other scenes from character "characters id"'
        );

        $chapter = $this->createMock(Chapter::class);

        $sceneAssigned = $this->createMock(Scene::class);
        $sceneAssigned->expects(self::exactly(1))->method('getChapter')->willReturn($chapter);

        $unassignedSceneId = $this->createMock(UuidInterface::class);
        $unassignedSceneId->expects(self::once())->method('toString')->willReturn('unassigned scene id');
        $sceneUnassigned = $this->createMock(Scene::class);
        $sceneUnassigned->expects(self::once())->method('getId')->willReturn($unassignedSceneId);
        $sceneUnassigned->expects(self::exactly(1))->method('getChapter')->willReturn(null);

        $characterId = $this->createMock(UuidInterface::class);
        $characterId->expects(self::once())->method('toString')->willReturn('characters id');
        $character = new Character(
            $characterId,
            $sceneAssigned,
            new ShortText('Character with inconsistent scenes'),
            null,
            null,
            $this->createMock(Author::class)
        );
        $character->addScene($sceneUnassigned);
    }

    public function testExceptionWhenTheNewChapterHasADifferentBook(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Scene "scene with a book id" is inconsistent with other scenes from character "character with a book id"'
        );

        // Scene 1
        $book = $this->createMock(Book::class);
        $chapterWithABook = $this->createMock(Chapter::class);
        $chapterWithABook->expects(self::once())->method('getBook')->willReturn($book);

        $sceneWithABook = $this->createMock(Scene::class);
        $sceneWithABook->expects(self::once())->method('getChapter')->willReturn($chapterWithABook);

        // Scene 2
        $differentBook = $this->createMock(Book::class);

        $chapterWithADifferentBook = $this->createMock(Chapter::class);
        $chapterWithADifferentBook->expects(self::once())->method('getBook')->willReturn($differentBook);

        $sceneWithADifferentBookId = $this->createMock(UuidInterface::class);
        $sceneWithADifferentBookId->expects(self::once())->method('toString')->willReturn('scene with a book id');
        $sceneWithADifferentBook = $this->createMock(Scene::class);
        $sceneWithADifferentBook->expects(self::once())->method('getId')->willReturn($sceneWithADifferentBookId);
        $sceneWithADifferentBook->expects(self::once())
            ->method('getChapter')
            ->willReturn($chapterWithADifferentBook)
        ;

        $characterId = $this->createMock(UuidInterface::class);
        $characterId->expects(self::once())->method('toString')->willReturn('character with a book id');
        $character = new Character(
            $characterId,
            $sceneWithABook,
            new ShortText('Character with inconsistent chapters'),
            null,
            null,
            $this->createMock(Author::class)
        );
        $character->addScene($sceneWithADifferentBook);
    }

    public function testNotRemovingFromOnlyScene(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage(
            'Cannot remove character "character id" from scene "scene 1", because it is it\'s only scene!'
        );

        $chapter = $this->createMock(Chapter::class);

        /** @var UuidInterface $sceneId */
        $sceneId = $this->makeEmpty(UuidInterface::class, ['toString' => 'scene 1']);
        /** @var Scene $scene1 */
        $scene1 = $this->makeEmpty(Scene::class, [
            'getId' => $sceneId,
            'getChapter' => $chapter
        ]);
        /** @var Scene $scene2 */
        $scene2 = $this->makeEmpty(Scene::class, ['getChapter' => $chapter]);

        /** @var UuidInterface $id */
        $id = $this->makeEmpty(UuidInterface::class, ['toString' => 'character id']);
        $character = new Character(
            $id,
            $scene1,
            new ShortText('Character'),
            null,
            null,
            $this->createMock(Author::class)
        );

        $character->addScene($scene2);

        $character->removeScene($scene2);
        $character->removeScene($scene1);
    }
}
