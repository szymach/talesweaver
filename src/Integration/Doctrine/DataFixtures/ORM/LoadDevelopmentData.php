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
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

final class LoadDevelopmentData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const BOOK_COUNT = 8;
    private const CHAPTER_COUNT = 10;
    private const SCENE_COUNT = 3;
    private const LOCALE = 'pl';

    public static function getGroups(): array
    {
        return ['development'];
    }

    public function load(ObjectManager $manager)
    {
        $author = $this->getReference(LoadUserData::AUTHOR);
        Assertion::isInstanceOf($author, Author::class);

        for ($i = 0; $i <= self::BOOK_COUNT; $i++) {
            $book = new Book(Uuid::uuid4(), new ShortText("Książka {$i}"), $author);
            $book->setLocale(self::LOCALE);
            $manager->persist($book);

            for ($j = 0; $j <= self::CHAPTER_COUNT; $j++) {
                $chapter = new Chapter(Uuid::uuid4(), new ShortText("Rozdział {$j}"), $book, $author);
                $chapter->setLocale(self::LOCALE);
                $book->addChapter($chapter);
                $manager->persist($chapter);

                for ($k = 0; $k <= self::SCENE_COUNT; $k++) {
                    $scene = new Scene(Uuid::uuid4(), new ShortText("Scena {$k}"), $chapter, $author);
                    $scene->setLocale(self::LOCALE);
                    $chapter->addScene($scene);
                    $manager->persist($scene);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [LoadUserData::class];
    }
}
