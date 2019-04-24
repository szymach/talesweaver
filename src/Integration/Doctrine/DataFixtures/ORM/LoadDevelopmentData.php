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
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

final class LoadDevelopmentData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const BOOK_COUNT = 8;
    private const CHAPTER_COUNT = 5;
    private const SCENE_COUNT = 6;
    private const CHARACTER_COUNT = 2;
    private const ITEM_COUNT = 3;
    private const LOCATION_COUNT = 4;
    private const LOCALE = 'pl';

    public static function getGroups(): array
    {
        return ['development'];
    }

    public function getDependencies(): array
    {
        return [LoadUserData::class];
    }

    public function load(ObjectManager $manager)
    {
        $author = $this->getReference(LoadUserData::AUTHOR);
        Assertion::isInstanceOf($author, Author::class);

        $this->createBooks($manager, $author);

        $manager->flush();
    }

    private function createBooks(ObjectManager $manager, Author $author): void
    {
        for ($i = 0; $i <= self::BOOK_COUNT; $i++) {
            $book = new Book(Uuid::uuid4(), new ShortText("Książka {$i}"), $author);
            $book->setLocale(self::LOCALE);
            $manager->persist($book);

            $this->addChaptersToBook($manager, $book);
        }
    }

    private function addChaptersToBook(ObjectManager $manager, Book $book): void
    {
        for ($i = 0; $i <= self::CHAPTER_COUNT; $i++) {
            $chapter = new Chapter(Uuid::uuid4(), new ShortText("Rozdział {$i}"), $book, $book->getCreatedBy());
            $chapter->setLocale(self::LOCALE);
            $book->addChapter($chapter);
            $manager->persist($chapter);

            $this->addScenesToChapter($manager, $chapter);
        }
    }

    private function addScenesToChapter(ObjectManager $manager, Chapter $chapter): void
    {
        for ($i = 0; $i <= self::SCENE_COUNT; $i++) {
            $scene = new Scene(Uuid::uuid4(), new ShortText("Scena {$i}"), $chapter, $chapter->getCreatedBy());
            $scene->setLocale(self::LOCALE);
            $this->addCharactersToScene($scene);
            $this->addItemsToScene($scene);
            $this->addLocationsToScene($scene);
            $chapter->addScene($scene);
            $manager->persist($scene);
        }
    }

    private function addCharactersToScene(Scene $scene): void
    {
        for ($i = 0; $i <= self::CHARACTER_COUNT; $i++) {
            $character = new Character(Uuid::uuid4(), $scene, new ShortText("Postać {$i}"), null, null, $scene->getCreatedBy());
            $character->setLocale(self::LOCALE);
            $scene->addCharacter($character);
        }
    }

    private function addItemsToScene(Scene $scene): void
    {
        for ($i = 0; $i <= self::ITEM_COUNT; $i++) {
            $item = new Item(Uuid::uuid4(), $scene, new ShortText("Przedmiot {$i}"), null, null, $scene->getCreatedBy());
            $item->setLocale(self::LOCALE);
            $scene->addItem($item);
        }
    }

    private function addLocationsToScene(Scene $scene): void
    {
        for ($i = 0; $i <= self::LOCATION_COUNT; $i++) {
            $location = new Location(Uuid::uuid4(), $scene, new ShortText("Miejsce {$i}"), null, null, $scene->getCreatedBy());
            $location->setLocale(self::LOCALE);
            $scene->addLocation($location);
        }
    }
}
