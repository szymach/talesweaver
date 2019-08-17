<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\Book;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\ValueObject\Email;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class PublicationTest extends TestCase
{
    public function testPublication(): void
    {
        $author = new Author(Uuid::uuid4(), new Email('email@example.com'), 'password', 'token', null, null);
        $book = new Book(Uuid::uuid4(), new ShortText('Książka'), $author);
        $book->setLocale('pl');
        $book->edit($book->getTitle(), null, null);

        $book->publish(
            new ShortText('Książka'),
            LongText::fromString('<div><h1>Tytuł</h1><p>Treść</p></div>'),
            false
        );
        $currentPublication = $book->getCurrentPublication('pl');
        self::assertNotNull($currentPublication);
        self::assertEquals(
            '<div><h1>Tytuł</h1><p>Treść</p></div>',
            $currentPublication->getContent()->getValue()
        );

        $book->publish(
            new ShortText('Książka'),
            LongText::fromString('<div><h1>Tytuł</h1><p>Treść zmieniona</p></div>'),
            true
        );
        $currentPublication = $book->getCurrentPublication('pl');
        self::assertNotNull($currentPublication);
        self::assertEquals(
            '<div><h1>Tytuł</h1><p>Treść zmieniona</p></div>',
            $currentPublication->getContent()->getValue()
        );

        self::assertCount(2, $book->getPublications());
    }
}
