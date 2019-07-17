<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\Scene;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class PublicationTest extends TestCase
{
    public function testPublication(): void
    {
        $author = new Author(Uuid::uuid4(), new Email('email@example.com'), 'password', 'token');
        $scene = new Scene(Uuid::uuid4(), new ShortText('Scena'), null, $author);
        $scene->setLocale('pl');
        $scene->edit($scene->getTitle(), null, null);

        $scene->publish(
            new ShortText('Scena'),
            LongText::fromString('<div><h1>Tytuł</h1><p>Treść</p></div>'),
            false
        );
        $currentPublication = $scene->getCurrentPublication('pl');
        self::assertNotNull($currentPublication);
        self::assertEquals(
            '<div><h1>Tytuł</h1><p>Treść</p></div>',
            $currentPublication->getContent()->getValue()
        );

        $scene->publish(
            new ShortText('Scena'),
            LongText::fromString('<div><h1>Tytuł</h1><p>Treść zmieniona</p></div>'),
            true
        );
        $currentPublication = $scene->getCurrentPublication('pl');
        self::assertNotNull($currentPublication);
        self::assertEquals(
            '<div><h1>Tytuł</h1><p>Treść zmieniona</p></div>',
            $currentPublication->getContent()->getValue()
        );

        self::assertCount(2, $scene->getPublications());
    }
}
