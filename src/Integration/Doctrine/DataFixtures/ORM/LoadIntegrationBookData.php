<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Assert\Assertion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\ValueObject\ShortText;

class LoadIntegrationBookData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const LOCALE = 'pl';

    public static function getGroups(): array
    {
        return ['integration'];
    }

    public function getDependencies(): array
    {
        return [LoadUserData::class];
    }

    public function load(ObjectManager $manager)
    {
        /* @var $author Author|null */
        $author = $this->getReference(LoadUserData::AUTHOR);
        Assertion::isInstanceOf($author, Author::class);

        $book = new Book(Uuid::uuid4(), new ShortText('Książka'), $author);
        $book->setLocale(self::LOCALE);

        $chapter = new Chapter(Uuid::uuid4(), new ShortText('Rozdział 1'), null, $book, $author);
        $chapter->setLocale(self::LOCALE);

        $manager->persist($book);
        $manager->persist($chapter);
        $manager->flush();
    }
}
