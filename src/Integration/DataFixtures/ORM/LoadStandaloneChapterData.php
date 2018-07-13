<?php

declare(strict_types=1);

namespace Talesweaver\Integration\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Chapter;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Item;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Entity\User;

class LoadStandaloneChapterData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        /* @var $user User */
        $user = $manager->getRepository(User::class)->findOneBy([]);
        $author = $user->getAuthor();

        $chapter = new Chapter(Uuid::uuid4(), 'Rozdział', null, $author);
        $chapter->setLocale(self::LOCALE);
        $scene1 = new Scene(Uuid::uuid4(), 'Scena 1', $chapter, $author);
        $scene2 = new Scene(Uuid::uuid4(), 'Scena 2', $chapter, $author);
        $scene1->setLocale(self::LOCALE);
        $scene2->setLocale(self::LOCALE);

        $character1 = new Character(Uuid::uuid4(), $scene1, 'Postać 1', '', null, $author);
        $character2 = new Character(Uuid::uuid4(), $scene2, 'Postać 2', '', null, $author);
        $character1->setLocale(self::LOCALE);
        $character2->setLocale(self::LOCALE);

        $item1 = new Item(Uuid::uuid4(), $scene1, 'Przedmiot 1', '', null, $author);
        $item2 = new Item(Uuid::uuid4(), $scene2, 'Przedmiot 2', '', null, $author);
        $item1->setLocale(self::LOCALE);
        $item2->setLocale(self::LOCALE);

        $location1 = new Location(Uuid::uuid4(), $scene1, 'Miejsce 1', '', null, $author);
        $location2 = new Location(Uuid::uuid4(), $scene2, 'Miejsce 2', '', null, $author);
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
