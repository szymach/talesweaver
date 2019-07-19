<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\Chapter;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class PublicationTest extends TestCase
{
    public function testPublication(): void
    {
        $author = new Author(Uuid::uuid4(), new Email('email@example.com'), 'password', 'token');
        $chapter = new Chapter(Uuid::uuid4(), new ShortText('Rozdział'), null, $author);
        $chapter->setLocale('pl');
        $chapter->edit($chapter->getTitle(), null, null);

        $chapter->publish(
            new ShortText('Rozdział'),
            LongText::fromString('<div><h1>Tytuł</h1><p>Treść</p></div>'),
            false
        );
        $currentPublication = $chapter->getCurrentPublication('pl');
        self::assertNotNull($currentPublication);
        self::assertEquals(
            '<div><h1>Tytuł</h1><p>Treść</p></div>',
            $currentPublication->getContent()->getValue()
        );

        $chapter->publish(
            new ShortText('Rozdział'),
            LongText::fromString('<div><h1>Tytuł</h1><p>Treść zmieniona</p></div>'),
            true
        );
        $currentPublication = $chapter->getCurrentPublication('pl');
        self::assertNotNull($currentPublication);
        self::assertEquals(
            '<div><h1>Tytuł</h1><p>Treść zmieniona</p></div>',
            $currentPublication->getContent()->getValue()
        );

        self::assertCount(2, $chapter->getPublications());
    }
}
