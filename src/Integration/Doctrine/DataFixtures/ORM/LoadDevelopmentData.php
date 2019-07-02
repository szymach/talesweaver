<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Assert\Assertion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Book;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Event;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

final class LoadDevelopmentData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const BOOK_COUNT = 5;
    private const CHAPTER_COUNT = 5;
    private const SCENE_COUNT = 6;
    private const CHARACTER_COUNT = 2;
    private const ITEM_COUNT = 3;
    private const LOCATION_COUNT = 4;
    private const EVENT_COUNT = 4;
    private const LOCALE = 'pl';

    /**
     * @var Generator|null
     */
    private $faker;

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
        for ($i = 1; $i <= self::BOOK_COUNT; $i++) {
            $book = new Book(Uuid::uuid4(), new ShortText("Książka {$i}"), $author);
            $book->setLocale(self::LOCALE);
            $manager->persist($book);

            $this->addChaptersToBook($manager, $book, $i);
        }
    }

    private function addChaptersToBook(ObjectManager $manager, Book $book, int $bookIndex): void
    {
        for ($i = 1; $i <= self::CHAPTER_COUNT; $i++) {
            $chapter = new Chapter(
                Uuid::uuid4(),
                new ShortText("Rozdział {$i} {$bookIndex}"),
                $book,
                $book->getCreatedBy()
            );
            $chapter->setLocale(self::LOCALE);
            $book->addChapter($chapter);
            $manager->persist($chapter);

            $this->addScenesToChapter($manager, $chapter, $bookIndex, $i);
        }
    }

    private function addScenesToChapter(ObjectManager $manager, Chapter $chapter, int $bookIndex, $chapterIndex): void
    {
        for ($i = 1; $i <= self::SCENE_COUNT; $i++) {
            $title = new ShortText("Scena {$i} {$bookIndex} {$chapterIndex}");
            $scene = new Scene(Uuid::uuid4(), $title, $chapter, $chapter->getCreatedBy());
            $scene->edit($title, LongText::fromNullableString($this->createRandomText(40)), $chapter);
            $scene->setLocale(self::LOCALE);

            $this->addCharactersToScene($scene);
            $this->addItemsToScene($scene);
            $this->addLocationsToScene($scene);
            $this->addEventsToScene($manager, $scene);

            $manager->persist($scene);

            $chapter->addScene($scene);
        }
    }

    private function addCharactersToScene(Scene $scene): void
    {
        for ($i = 1; $i <= self::CHARACTER_COUNT; $i++) {
            $character = new Character(
                Uuid::uuid4(),
                $scene,
                new ShortText("Postać {$i} ({$scene->getTitle()}) ({$scene->getChapter()->getTitle()})"),
                LongText::fromNullableString($this->createRandomText(5)),
                null,
                $scene->getCreatedBy()
            );
            $character->setLocale(self::LOCALE);
            $scene->addCharacter($character);
        }
    }

    private function addItemsToScene(Scene $scene): void
    {
        for ($i = 1; $i <= self::ITEM_COUNT; $i++) {
            $item = new Item(
                Uuid::uuid4(),
                $scene,
                new ShortText("Przedmiot {$i} ({$scene->getTitle()}) ({$scene->getChapter()->getTitle()})"),
                LongText::fromNullableString($this->createRandomText(5)),
                null,
                $scene->getCreatedBy()
            );
            $item->setLocale(self::LOCALE);
            $scene->addItem($item);
        }
    }

    private function addLocationsToScene(Scene $scene): void
    {
        for ($i = 1; $i <= self::LOCATION_COUNT; $i++) {
            $location = new Location(
                Uuid::uuid4(),
                $scene,
                new ShortText("Miejsce {$i} ({$scene->getTitle()}) ({$scene->getChapter()->getTitle()})"),
                LongText::fromNullableString($this->createRandomText(5)),
                null,
                $scene->getCreatedBy()
            );
            $location->setLocale(self::LOCALE);
            $scene->addLocation($location);
        }
    }

    private function addEventsToScene(ObjectManager $manager, Scene $scene): void
    {
        for ($i = 1; $i <= self::EVENT_COUNT; $i++) {
            $event = new Event(
                Uuid::uuid4(),
                new ShortText("Wydarzenie {$i} ({$scene->getTitle()})"),
                LongText::fromNullableString($this->createRandomText(5)),
                $this->getFaker()->randomElement($scene->getLocations()),
                $scene,
                $scene->getCreatedBy(),
                $this->getFaker()->randomElements($scene->getCharacters(), 2),
                $this->getFaker()->randomElements($scene->getItems(), 2)
            );
            $event->setLocale(self::LOCALE);
            $manager->persist($event);
        }
    }

    private function createRandomText(int $number): string
    {
        $text = $this->getFaker()->paragraphs($number, true);
        Assertion::string($text);

        return $text;
    }

    private function getFaker(): Generator
    {
        if (null === $this->faker) {
            $this->faker = Factory::create('pl_PL');
        }

        return $this->faker;
    }
}
