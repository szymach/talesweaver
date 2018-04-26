<?php

declare(strict_types=1);

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Entity\Character;
use Domain\Entity\Scene;
use Domain\Entity\User;
use Ramsey\Uuid\Uuid;

class LoadStandaloneSceneData implements ORMFixtureInterface, OrderedFixtureInterface
{
    private const LOCALE = 'pl';

    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy([]);
        $scene = new Scene(Uuid::uuid4(), 'Scena', null, $user);
        $scene->setLocale(self::LOCALE);

        $character = new Character(Uuid::uuid4(), $scene, 'Postać do spotkania', '', null, $user);
        $character->setLocale(self::LOCALE);

        $manager->persist($scene);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
