<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Author;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\ShortText;

class LoadStandaloneChapterData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        /* @var $author Author */
        $author = $manager->getRepository(Author::class)->findOneBy([]);

        $chapter = new Chapter(Uuid::uuid4(), new ShortText('Rozdział'), null, $author);
        $chapter->setLocale(self::LOCALE);
        $scene1 = new Scene(Uuid::uuid4(), new ShortText('Scena 1'), $chapter, $author);
        $scene2 = new Scene(Uuid::uuid4(), new ShortText('Scena 2'), $chapter, $author);
        $scene1->setLocale(self::LOCALE);
        $scene2->setLocale(self::LOCALE);

        $character1 = new Character(Uuid::uuid4(), $scene1, new ShortText('Postać 1'), null, null, $author);
        $character2 = new Character(Uuid::uuid4(), $scene2, new ShortText('Postać 2'), null, null, $author);
        $character1->setLocale(self::LOCALE);
        $character2->setLocale(self::LOCALE);

        $item1 = new Item(Uuid::uuid4(), $scene1, new ShortText('Przedmiot 1'), null, null, $author);
        $item2 = new Item(Uuid::uuid4(), $scene2, new ShortText('Przedmiot 2'), null, null, $author);
        $item1->setLocale(self::LOCALE);
        $item2->setLocale(self::LOCALE);

        $location1 = new Location(Uuid::uuid4(), $scene1, new ShortText('Miejsce 1'), null, null, $author);
        $location2 = new Location(Uuid::uuid4(), $scene2, new ShortText('Miejsce 2'), null, null, $author);
        $location1->setLocale(self::LOCALE);
        $location2->setLocale(self::LOCALE);

        $manager->persist($chapter);
        $manager->persist($scene1);
        $manager->persist($scene2);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
