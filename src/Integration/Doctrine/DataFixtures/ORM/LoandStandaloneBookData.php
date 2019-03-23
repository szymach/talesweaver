<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Assert\Assertion;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

class LoandStandaloneBookData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        /* @var $author Author */
        $author = $manager->getRepository(Author::class)->findOneBy([]);
        Assertion::notNull($author);

        $book = new Book(Uuid::uuid4(), new ShortText('Książka'), $author);
        $book->setLocale(self::LOCALE);

        $chapter = new Chapter(Uuid::uuid4(), new ShortText('Rozdział 1'), $book, $author);
        $chapter->setLocale(self::LOCALE);

        $manager->persist($book);
        $manager->persist($chapter);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
