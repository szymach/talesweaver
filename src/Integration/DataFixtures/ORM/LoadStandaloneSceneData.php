<?php

declare(strict_types=1);

namespace Talesweaver\Integration\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\Location;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Doctrine\Entity\User;

class LoadStandaloneSceneData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        /* @var $user User */
        $user = $manager->getRepository(User::class)->findOneBy([]);
        $author = $user->getAuthor();

        $scene = new Scene(Uuid::uuid4(), 'Scena', null, $author);
        $scene->setLocale(self::LOCALE);

        $character1 = new Character(Uuid::uuid4(), $scene, 'Postać do spotkania 1', '', null, $author);
        $character2 = new Character(Uuid::uuid4(), $scene, 'Postać do spotkania 2', '', null, $author);
        $character3 = new Character(Uuid::uuid4(), $scene, 'Postać do spotkania 3', '', null, $author);
        $character1->setLocale(self::LOCALE);
        $character2->setLocale(self::LOCALE);
        $character3->setLocale(self::LOCALE);

        $location1 = new Location(Uuid::uuid4(), $scene, 'Miejsce do spotkania 1', '', null, $author);
        $location2 = new Location(Uuid::uuid4(), $scene, 'Miejsce do spotkania 2', '', null, $author);
        $location1->setLocale(self::LOCALE);
        $location2->setLocale(self::LOCALE);

        $manager->persist($scene);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
