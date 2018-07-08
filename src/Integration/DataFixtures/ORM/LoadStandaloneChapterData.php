<?php

declare(strict_types=1);

namespace Integration\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Chapter;
use Domain\Character;
use Domain\Item;
use Domain\Location;
use Domain\Scene;
use Domain\User;
use Ramsey\Uuid\Uuid;

class LoadStandaloneChapterData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy([]);

        $chapter = new Chapter(Uuid::uuid4(), 'Rozdział', null, $user);
        $chapter->setLocale(self::LOCALE);
        $scene1 = new Scene(Uuid::uuid4(), 'Scena 1', $chapter, $user);
        $scene2 = new Scene(Uuid::uuid4(), 'Scena 2', $chapter, $user);
        $scene1->setLocale(self::LOCALE);
        $scene2->setLocale(self::LOCALE);

        $character1 = new Character(Uuid::uuid4(), $scene1, 'Postać 1', '', null, $user);
        $character2 = new Character(Uuid::uuid4(), $scene2, 'Postać 2', '', null, $user);
        $character1->setLocale(self::LOCALE);
        $character2->setLocale(self::LOCALE);

        $item1 = new Item(Uuid::uuid4(), $scene1, 'Przedmiot 1', '', null, $user);
        $item2 = new Item(Uuid::uuid4(), $scene2, 'Przedmiot 2', '', null, $user);
        $item1->setLocale(self::LOCALE);
        $item2->setLocale(self::LOCALE);

        $location1 = new Location(Uuid::uuid4(), $scene1, 'Miejsce 1', '', null, $user);
        $location2 = new Location(Uuid::uuid4(), $scene2, 'Miejsce 2', '', null, $user);
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
